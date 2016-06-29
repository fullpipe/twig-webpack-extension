<?php

namespace Fullpipe\Twig\Extension\Webpack;

/**
 * EntryTokenParser.
 */
class EntryTokenParser extends \Twig_TokenParser
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
    public function __construct($manifestFile, $publicPath)
    {
        $this->manifestFile = $manifestFile;
        $this->publicPath = $publicPath;
    }

    /**
     * Parses a token and returns a node.
     *
     * @param Twig_Token $token A Twig_Token instance
     *
     * @return Twig_Node_Text
     *
     * @throws Twig_Error_Loader
     */
    public function parse(\Twig_Token $token)
    {
        $stream = $this->parser->getStream();
        $entryName = $stream->expect(\Twig_Token::STRING_TYPE)->getValue();
        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        if (!file_exists($this->manifestFile)) {
            throw new \Twig_Error_Loader('Webpack manifest file not exists.', $token->getLine(), $stream->getFilename());
        }

        $manifest = json_decode(file_get_contents($this->manifestFile), true);
        $assets = [];

        if (isset($manifest[$entryName.'.js']) || isset($manifest[$entryName.'.css'])) {
            if (isset($manifest[$entryName.'.css'])) {
                $entryPath = $this->publicPath.$manifest[$entryName.'.css'];
                $assets[] = '<link rel="stylesheet" href="'.$entryPath.'">';
            }

            if (isset($manifest[$entryName.'.js'])) {
                $entryPath = $this->publicPath.$manifest[$entryName.'.js'];
                $assets[] = '<script type="text/javascript" src="'.$entryPath.'"></script>';
            }
        } else {
            throw new \Twig_Error_Loader('Webpack entry '.$entryName.' not exists.', $token->getLine(), $stream->getFilename());
        }

        return new \Twig_Node_Text(implode('', $assets), $token->getLine());
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'webpack_entry';
    }
}
