<?php

namespace Fullpipe\TwigWebpackExtension;

use Fullpipe\TwigWebpackExtension\TokenParser\EntryTokenParserCss;
use Fullpipe\TwigWebpackExtension\TokenParser\EntryTokenParserJs;
use Twig\Extension\AbstractExtension;

class WebpackExtension extends AbstractExtension
{
    /**
     * @var string
     */
    protected $manifestFile;

    /**
     * @var string
     */
    protected $publicPathJs;

    /**
     * @var string
     */
    protected $publicPathCss;

    public function __construct(string $manifestFile, string $publicPathJs = '/js/', string $publicPathCss = '/css/')
    {
        $this->manifestFile = $manifestFile;
        $this->publicPathJs = $publicPathJs;
        $this->publicPathCss = $publicPathCss;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fullpipe.extension.webpack';
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return [
            new EntryTokenParserJs($this->manifestFile, $this->publicPathJs),
            new EntryTokenParserCss($this->manifestFile, $this->publicPathCss),
        ];
    }
}
