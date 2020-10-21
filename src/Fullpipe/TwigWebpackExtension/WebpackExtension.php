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
    protected $publicDir;

    public function __construct(string $manifestFile, string $publicDir)
    {
        $this->manifestFile = $manifestFile;
        $this->publicDir = $publicDir;
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
            new EntryTokenParserJs($this->manifestFile, $this->publicDir),
            new EntryTokenParserCss($this->manifestFile, $this->publicDir),
        ];
    }
}
