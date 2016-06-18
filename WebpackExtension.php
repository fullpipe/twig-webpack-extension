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
    protected $publicPath;

    /**
     * Constructor.
     *
     * @param string $manifestFile absolute path to your manifest.json
     * @param string $publicPath   your webpack output.publicPath
     */
    public function __construct($manifestFile, $publicPath = '/build/')
    {
        $this->manifestFile = $manifestFile;
        $this->publicPath = $publicPath;
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
        return array(new EntryTokenParser($this->manifestFile, $this->publicPath));
    }
}
