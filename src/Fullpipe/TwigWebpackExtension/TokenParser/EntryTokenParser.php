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

    abstract protected function generateHtml($entryPath);

    public function __construct($manifestFile, $publicPath)
    {
        $this->manifestFile = $manifestFile;
        $this->publicPath = $publicPath;
    }

    public function parse(Token $token)
    {
        $stream = $this->parser->getStream();
        $entryName = $stream->expect(Token::STRING_TYPE)->getValue();
        $stream->expect(Token::BLOCK_END_TYPE);

        if (!file_exists($this->manifestFile)) {
            throw new LoaderError(
                'Webpack manifest file not exists.',
                $token->getLine(),
                $stream->getSourceContext()->getName()
            );
        }

        $manifest = json_decode(file_get_contents($this->manifestFile), true);
        $assets = [];

        $manifestIndex = $entryName . '.' . $this->type();

        if (isset($manifest[$manifestIndex])) {
            $entryPath = $this->publicPath . $manifest[$manifestIndex];

            $assets[] = $this->generateHtml($entryPath);
        } else {
            throw new LoaderError(
                'Webpack ' . $this->type() . ' entry ' . $entryName . ' not exists.',
                $token->getLine(),
                $stream->getSourceContext()->getName()
            );
        }

        return new TextNode(implode('', $assets), $token->getLine());
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return 'webpack_entry_' . $this->type();
    }
}
