<?php

namespace Fullpipe\TwigWebpackExtension;

use Fullpipe\TwigWebpackExtension\TokenParser\EntryTokenParserCss;
use Fullpipe\TwigWebpackExtension\TokenParser\EntryTokenParserJs;

class WebpackExtension extends \Twig_Extension
{
    protected $manifestFile;
    protected $publicPathJs;
    protected $publicPathCss;
    protected $publicPathFile;

    public function __construct($manifestFile, $publicPathJs = '/js/', $publicPathCss = '/css/', $publicPathFile = '/file/')
    {
        $this->manifestFile = $manifestFile;
        $this->publicPathJs = $publicPathJs;
        $this->publicPathCss = $publicPathCss;

        $this->publicPathFile = $publicPathFile;
    }

    public function getName()
    {
        return 'fullpipe.extension.webpack';
    }

    public function getTokenParsers()
    {
        return [
            new EntryTokenParserJs($this->manifestFile, $this->publicPathJs),
            new EntryTokenParserCss($this->manifestFile, $this->publicPathCss),
            new EntryTokenParserFile($this->manifestFile, $this->publicPathFile),
        ];
    }
}
