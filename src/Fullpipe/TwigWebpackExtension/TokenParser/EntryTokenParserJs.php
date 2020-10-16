<?php

namespace Fullpipe\TwigWebpackExtension\TokenParser;

use Twig\Error\LoaderError;
use Twig\Node\TextNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class EntryTokenParserJs extends AbstractTokenParser
{
    /**
     * @var string
     */
    private $manifestFile;

    /**
     * @var string
     */
    private $publicDir;

    public function __construct(string $manifestFile, string $publicDir)
    {
        $this->manifestFile = $manifestFile;
        $this->publicDir = $publicDir;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(Token $token)
    {
        $stream = $this->parser->getStream();
        $entryName = $stream->expect(Token::STRING_TYPE)->getValue();
        $defer = $stream->nextIf(/* Token::NAME_TYPE */ 5, 'defer');
        $async = $stream->nextIf(/* Token::NAME_TYPE */ 5, 'async');
        $inline = $stream->nextIf(/* Token::NAME_TYPE */ 5, 'inline');
        $stream->expect(Token::BLOCK_END_TYPE);

        if (!\file_exists($this->manifestFile)) {
            throw new LoaderError('Webpack manifest file not exists.', $token->getLine(), $stream->getSourceContext());
        }

        $manifest = \json_decode(\file_get_contents($this->manifestFile), true);
        $manifestIndex = $entryName.'.js';

        if (!isset($manifest[$manifestIndex])) {
            throw new LoaderError('Webpack js entry '.$entryName.' not exists.', $token->getLine(), $stream->getSourceContext());
        }

        $entryPath = $manifest[$manifestIndex];

        if ($inline) {
            $tag = \sprintf(
                '<script type="text/javascript">%s</script>',
                $this->getEntryContent($entryPath)
            );
        } else {
            $tag = \sprintf(
                '<script type="text/javascript" src="%s"%s></script>',
                $entryPath,
                $defer
                ? ' defer'
                : ($async ? ' async' : '')
            );
        }

        return new TextNode($tag, $token->getLine());
    }

    /**
     * @throws LoaderError if file does not exists or not readable
     */
    private function getEntryContent(string $entryFile): ?string
    {
        $entryFile = \trim($entryFile, '/');

        if (!\file_exists($this->publicDir.'/'.$entryFile)) {
            throw new LoaderError(\sprintf('Entry file "%s" does not exists.', $this->publicDir.'/'.$entryFile));
        }

        $content = \file_get_contents($this->publicDir.'/'.$entryFile);
        if (false === $content) {
            throw new LoaderError(\sprintf('Unable to read file "%s".', $this->publicDir.'/'.$entryFile));
        }

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getTag(): string
    {
        return 'webpack_entry_js';
    }
}
