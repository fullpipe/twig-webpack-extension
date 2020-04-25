<?php

namespace tests\Fullpipe\TwigWebpackExtension\TokenParser;

use Fullpipe\TwigWebpackExtension\TokenParser\EntryTokenParserJs;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\LoaderInterface;
use Twig\Node\TextNode;
use Twig\Parser;
use Twig\Source;
use Twig\TokenParser\AbstractTokenParser;

class EntryTokenParserJsTest extends TestCase
{
    public function testItIsAParser()
    {
        $this->assertInstanceOf(AbstractTokenParser::class, new EntryTokenParserJs(__DIR__.'/../Resource/manifest.json', '/build/'));
    }

    public function testGetTag()
    {
        $parser = new EntryTokenParserJs(__DIR__.'/../Resource/manifest.json', '/build/');
        $this->assertEquals('webpack_entry_js', $parser->getTag());
    }

    public function testGenerate()
    {
        $env = $this->getEnv(__DIR__.'/../Resource/manifest.json', '/build/');
        $parser = new Parser($env);
        $source = new Source("{% webpack_entry_js 'main' %}", '');
        $stream = $env->tokenize($source);

        $expected = new TextNode('<script type="text/javascript" src="/build/main.js"></script>', 1);
        $expected->setSourceContext($source);

        $this->assertEquals(
            $expected,
            $parser->parse($stream)->getNode('body')->getNode('0')
        );
    }

    public function testGenerateDefer()
    {
        $env = $this->getEnv(__DIR__.'/../Resource/manifest.json', '/build/');
        $parser = new Parser($env);
        $source = new Source("{% webpack_entry_js 'main' defer %}", '');
        $stream = $env->tokenize($source);

        $expected = new TextNode('<script type="text/javascript" src="/build/main.js" defer></script>', 1);
        $expected->setSourceContext($source);

        $this->assertEquals(
            $expected,
            $parser->parse($stream)->getNode('body')->getNode('0')
        );
    }

    public function testGenerateAsync()
    {
        $env = $this->getEnv(__DIR__.'/../Resource/manifest.json', '/build/');
        $parser = new Parser($env);
        $source = new Source("{% webpack_entry_js 'main' async %}", '');
        $stream = $env->tokenize($source);

        $expected = new TextNode('<script type="text/javascript" src="/build/main.js" async></script>', 1);
        $expected->setSourceContext($source);

        $this->assertEquals(
            $expected,
            $parser->parse($stream)->getNode('body')->getNode('0')
        );
    }

    public function testGenerateInline()
    {
        $env = $this->getEnv(__DIR__.'/../Resource/manifest.json', '/build/');
        $parser = new Parser($env);
        $source = new Source("{% webpack_entry_js 'main' inline %}", '');
        $stream = $env->tokenize($source);

        $expected = new TextNode("<script type=\"text/javascript\">alert('foo');\n</script>", 1);
        $expected->setSourceContext($source);

        $this->assertEquals(
            $expected,
            $parser->parse($stream)->getNode('body')->getNode('0')
        );
    }

    private function getEnv(string $manifest, string $publicPath): Environment
    {
        $env = new Environment(
            $this->getMockBuilder(LoaderInterface::class)->getMock(),
            ['cache' => false, 'autoescape' => false, 'optimizations' => 0]
        );
        $env->addTokenParser(new EntryTokenParserJs($manifest, $publicPath));

        return $env;
    }
}
