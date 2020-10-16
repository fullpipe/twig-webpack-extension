<?php

namespace tests\Fullpipe\TwigWebpackExtension\TokenParser;

use Fullpipe\TwigWebpackExtension\TokenParser\EntryTokenParserCss;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Loader\LoaderInterface;
use Twig\Node\TextNode;
use Twig\Parser;
use Twig\Source;
use Twig\TokenParser\AbstractTokenParser;

class EntryTokenParserCssTest extends TestCase
{
    public function testItIsAParser()
    {
        $this->assertInstanceOf(AbstractTokenParser::class, new EntryTokenParserCss(__DIR__.'/../Resource/build/manifest.json', '/build/'));
    }

    public function testGetTag()
    {
        $parser = new EntryTokenParserCss(__DIR__.'/../Resource/build/manifest.json', '/build/');
        $this->assertEquals('webpack_entry_css', $parser->getTag());
    }

    public function testBasicParse()
    {
        $env = $this->getEnv(__DIR__.'/../Resource/build/manifest.json', '/build/');
        $parser = new Parser($env);
        $source = new Source("{% webpack_entry_css 'main' %}", '');
        $stream = $env->tokenize($source);

        $expected = new TextNode('<link type="text/css" href="/build/css/main.css" rel="stylesheet">', 1);
        $expected->setSourceContext($source);

        $this->assertEquals(
            $expected,
            $parser->parse($stream)->getNode('body')->getNode('0')
        );
    }

    public function testInline()
    {
        $env = $this->getEnv(__DIR__.'/../Resource/build/manifest.json', '/build/');
        $parser = new Parser($env);
        $source = new Source("{% webpack_entry_css 'main' inline %}", '');
        $stream = $env->tokenize($source);

        $expected = new TextNode("<style>div { color: green; }\n</style>", 1);
        $expected->setSourceContext($source);

        $this->assertEquals(
            $expected,
            $parser->parse($stream)->getNode('body')->getNode('0')
        );
    }

    public function testItThrowsExceptionIfNoManifest()
    {
        $this->expectException(LoaderError::class);

        $env = $this->getEnv(__DIR__.'/../Resource/not_exists.json', '/build/');
        $parser = new Parser($env);
        $source = new Source("{% webpack_entry_css 'main' %}", '');
        $stream = $env->tokenize($source);
        $parser->parse($stream);
    }

    public function testItThrowsExceptionIfEntryNotExists()
    {
        $this->expectException(LoaderError::class);

        $env = $this->getEnv(__DIR__.'/../Resource/build/manifest.json', '/build/');
        $parser = new Parser($env);
        $source = new Source("{% webpack_entry_css 'not_exists' %}", '');
        $stream = $env->tokenize($source);
        $parser->parse($stream);
    }

    private function getEnv(string $manifest, string $publicPath): Environment
    {
        $env = new Environment(
            $this->createMock(LoaderInterface::class),
            ['cache' => false, 'autoescape' => false, 'optimizations' => 0]
        );
        $env->addTokenParser(new EntryTokenParserCss($manifest, $publicPath));

        return $env;
    }
}
