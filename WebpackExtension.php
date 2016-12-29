<?php

namespace Fullpipe\Twig\Extension\Webpack;

/**
 * WebpackExtension.
 */
class WebpackExtension extends \Twig_Extension
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

    /**
     * Constructor.
     *
     * @param string $manifestFile  absolute path to your manifest.json
     * @param string $publicPathJs  your webpack output.publicPath
     * @param string $publicPathCss your webpack output.publicPath
     */
    public function __construct($manifestFile, $publicPathJs = '/js/', $publicPathCss = '/css/')
    {
        $this->manifestFile = $manifestFile;
        $this->publicPathJs = $publicPathJs;
        $this->publicPathCss = $publicPathCss;
    }

    /**
     * {@inheritdoc}
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
