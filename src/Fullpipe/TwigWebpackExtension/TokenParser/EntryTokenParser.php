<?php

namespace Fullpipe\TwigWebpackExtension\TokenParser;

use Twig\Error\LoaderError;
use Twig\Node\TextNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

abstract class EntryTokenParser extends AbstractTokenParser
{
    protected $manifestFile;
    protected $publicPath;

    abstract protected function type();

    abstract protected function generateHtml($entryPath, bool $defer);

    public function __construct($manifestFile, $publicPath)
    {
        $this->manifestFile = $manifestFile;
        $this->publicPath = $publicPath;
    }

    public function parse(Token $token)
    {
        if (!file_exists($this->manifestFile)) {
            throw new LoaderError(
                'Webpack manifest file not exists.',
                $token->getLine());
        }

        $stream = $this->parser->getStream();
        $entryName = $stream->expect(Token::STRING_TYPE)->getValue();
        $defer = $stream->test(Token::STRING_TYPE) ? $stream->expect(Token::STRING_TYPE)->getValue() === 'defer' : false;
        $stream->expect(Token::BLOCK_END_TYPE);

        $manifest = json_decode(file_get_contents($this->manifestFile), true);
        $assets = [];

        if (!isset($manifest[$entryName . '.' . $this->type()])) {
            throw new LoaderError(
                'Webpack ' . $this->type() . ' entry ' . $entryName . ' not exists.',
                $token->getLine()
            );
        }

        $entryPath = $this->publicPath . $manifest[$entryName . '.' . $this->type()];
        $assets[] = $this->generateHtml($entryPath, $defer);
        return new TextNode(implode('', $assets), $token->getLine());
    }

    public function getTag()
    {
        return 'webpack_entry_' . $this->type();
    }
}
