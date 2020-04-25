<?php

namespace Fullpipe\TwigWebpackExtension\TokenParser;

use Twig\Error\LoaderError;
use Twig\Node\TextNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class EntryTokenParserCss extends AbstractTokenParser
{
    /**
     * @var string
     */
    private $manifestFile;

    /**
     * @var string
     */
    private $publicPath;

    public function __construct(string $manifestFile, string $publicPath)
    {
        $this->manifestFile = $manifestFile;
        $this->publicPath = $publicPath;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Token $token)
    {
        $stream = $this->parser->getStream();
        $entryName = $stream->expect(Token::STRING_TYPE)->getValue();
        $inline = $stream->nextIf(/* Token::NAME_TYPE */ 5, 'inline');
        $stream->expect(Token::BLOCK_END_TYPE);

        if (!\file_exists($this->manifestFile)) {
            throw new LoaderError('Webpack manifest file not exists.', $token->getLine(), $stream->getSourceContext());
        }

        $manifest = \json_decode(\file_get_contents($this->manifestFile), true);
        $manifestIndex = $entryName.'.css';

        if (!isset($manifest[$manifestIndex])) {
            throw new LoaderError('Webpack css entry '.$entryName.' not exists.', $token->getLine(), $stream->getSourceContext());
        }

        $entryPath = $manifest[$manifestIndex];

        if ($inline) {
            $tag = \sprintf(
                '<style>%s</style>',
                $this->getEntryContent($entryPath)
            );
        } else {
            $tag = \sprintf(
                '<link type="text/css" href="%s" rel="stylesheet">',
                $entryPath
            );
        }

        return new TextNode($tag, $token->getLine());
    }

    /**
     * @throws Exception if file does not exists
     */
    private function getEntryContent(string $entryFile): ?string
    {
        $dir = \dirname($this->manifestFile);
        $entryFile = \str_replace($this->publicPath, '', $entryFile);

        if (!\file_exists($dir.'/'.$entryFile)) {
            throw new LoaderError(\sprintf('Entry file "%s" does not exists.', $dir.'/'.$entryFile));
        }

        return \file_get_contents($dir.'/'.$entryFile);
    }

    /**
     * {@inheritdoc}
     */
    public function getTag(): string
    {
        return 'webpack_entry_css';
    }
}
