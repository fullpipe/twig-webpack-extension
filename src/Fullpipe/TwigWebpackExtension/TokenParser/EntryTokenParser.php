<?php

namespace Fullpipe\TwigWebpackExtension\TokenParser;

use Twig\Error\LoaderError;
use Twig\Node\TextNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

abstract class EntryTokenParser extends AbstractTokenParser
{
    /**
     * @var string
     */
    protected $manifestFile;

    /**
     * @var string
     */
    protected $publicPath;

    /**
     * Entry extention.
     */
    abstract protected function type(): string;

    abstract protected function generateHtml(string $entryPath): string;

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
        $stream->expect(Token::BLOCK_END_TYPE);

        if (!\file_exists($this->manifestFile)) {
            throw new LoaderError('Webpack manifest file not exists.', $token->getLine(), $stream->getSourceContext());
        }

        $manifest = \json_decode(\file_get_contents($this->manifestFile), true);
        $assets = [];

        $manifestIndex = $entryName.'.'.$this->type();

        if (isset($manifest[$manifestIndex])) {
            $entryPath = $this->publicPath.$manifest[$manifestIndex];

            $assets[] = $this->generateHtml($entryPath);
        } else {
            throw new LoaderError('Webpack '.$this->type().' entry '.$entryName.' not exists.', $token->getLine(), $stream->getSourceContext());
        }

        return new TextNode(\implode('', $assets), $token->getLine());
    }

    /**
     * Get twig tag name.
     */
    public function getTag(): string
    {
        return 'webpack_entry_'.$this->type();
    }
}
