<?php

namespace Fullpipe\TwigWebpackExtension;

use Fullpipe\TwigWebpackExtension\TokenParser\EntryTokenParserCss;
use Fullpipe\TwigWebpackExtension\TokenParser\EntryTokenParserJs;
use Twig\Extension\AbstractExtension;

class WebpackExtension extends AbstractExtension
{
    protected $manifestFile;
    protected $publicPathJs;
    protected $publicPathCss;

    public function __construct($manifestFile, $publicPathJs = '/js/', $publicPathCss = '/css/')
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
     * @return array
     */
    public function getTokenParsers()
    {
        return [
            new EntryTokenParserJs($this->manifestFile, $this->publicPathJs),
            new EntryTokenParserCss($this->manifestFile, $this->publicPathCss),
        ];
    }
}
