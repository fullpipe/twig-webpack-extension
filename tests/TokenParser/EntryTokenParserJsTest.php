<?php

namespace tests\Fullpipe\TwigWebpackExtension\TokenParser;

use Fullpipe\TwigWebpackExtension\TokenParser\EntryTokenParserJs;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Loader\LoaderInterface;
use Twig\Node\TextNode;
use Twig\Parser;
use Twig\Source;
use Twig\TokenParser\AbstractTokenParser;

class EntryTokenParserJsTest extends TestCase
{
    public function testItIsAParser()
    {
        $this->assertInstanceOf(AbstractTokenParser::class, new EntryTokenParserJs(__DIR__.'/../Resource/build/manifest.json', __DIR__.'/../Resource'));
    }

    public function testGetTag()
    {
        $parser = new EntryTokenParserJs(__DIR__.'/../Resource/build/manifest.json', __DIR__.'/../Resource');
        $this->assertEquals('webpack_entry_js', $parser->getTag());
    }

    public function testBasic()
    {
        $env = $this->getEnv(__DIR__.'/../Resource/build/manifest.json', __DIR__.'/../Resource');
        $parser = new Parser($env);
        $source = new Source("{% webpack_entry_js 'main' %}", '');
        $stream = $env->tokenize($source);

        $expected = new TextNode('<script type="text/javascript" src="/build/js/main.js"></script>', 1);
        $expected->setSourceContext($source);

        $this->assertEquals(
            $expected,
            $parser->parse($stream)->getNode('body')->getNode('0')
        );
    }

    public function testDefer()
    {
        $env = $this->getEnv(__DIR__.'/../Resource/build/manifest.json', __DIR__.'/../Resource');
        $parser = new Parser($env);
        $source = new Source("{% webpack_entry_js 'main' defer %}", '');
        $stream = $env->tokenize($source);

        $expected = new TextNode('<script type="text/javascript" src="/build/js/main.js" defer></script>', 1);
        $expected->setSourceContext($source);

        $this->assertEquals(
            $expected,
            $parser->parse($stream)->getNode('body')->getNode('0')
        );
    }

    public function testAsync()
    {
        $env = $this->getEnv(__DIR__.'/../Resource/build/manifest.json', __DIR__.'/../Resource');
        $parser = new Parser($env);
        $source = new Source("{% webpack_entry_js 'main' async %}", '');
        $stream = $env->tokenize($source);

        $expected = new TextNode('<script type="text/javascript" src="/build/js/main.js" async></script>', 1);
        $expected->setSourceContext($source);

        $this->assertEquals(
            $expected,
            $parser->parse($stream)->getNode('body')->getNode('0')
        );
    }

    public function testInline()
    {
        $env = $this->getEnv(__DIR__.'/../Resource/build/manifest.json', __DIR__.'/../Resource');
        $parser = new Parser($env);
        $source = new Source("{% webpack_entry_js 'second' inline %}", '');
        $stream = $env->tokenize($source);

        $expected = new TextNode("<script type=\"text/javascript\">alert(\"second\");\n</script>", 1);
        $expected->setSourceContext($source);

        $this->assertEquals(
            $expected,
            $parser->parse($stream)->getNode('body')->getNode('0')
        );
    }

    public function testItThrowsExceptionIfManifestIsUnreadable()
    {
        $env = $this->getEnv(__DIR__.'/../Resource/build/no_manifest.json', __DIR__.'/../Resource');
        $parser = new Parser($env);
        $source = new Source("{% webpack_entry_js 'second' inline %}", '');
        $stream = $env->tokenize($source);

        $this->expectException(LoaderError::class);

        $parser->parse($stream)->getNode('body')->getNode('0');
    }

    public function testItThrowsExceptionIfEntryIsUnreadable()
    {
        $env = $this->getEnv(__DIR__.'/../Resource/build/manifest.json', __DIR__.'/../Resource/fake_public_dir');
        $parser = new Parser($env);
        $source = new Source("{% webpack_entry_js 'second' inline %}", '');
        $stream = $env->tokenize($source);

        $this->expectException(LoaderError::class);

        $parser->parse($stream)->getNode('body')->getNode('0');
    }

    private function getEnv(string $manifest, string $publicDir): Environment
    {
        $env = new Environment(
            $this->createMock(LoaderInterface::class),
            ['cache' => false, 'autoescape' => false, 'optimizations' => 0]
        );
        $env->addTokenParser(new EntryTokenParserJs($manifest, $publicDir));

        return $env;
    }
}
