<?php

namespace Fullpipe\TwigWebpackExtension\Tests;

use Fullpipe\TwigWebpackExtension\TokenParser\EntryTokenParserCss;
use Fullpipe\TwigWebpackExtension\TokenParser\EntryTokenParserJs;
use Fullpipe\TwigWebpackExtension\WebpackExtension;
use PHPUnit\Framework\TestCase;

class WebpackExtensionTest extends TestCase
{
    public function testGetName()
    {
        $extension = new WebpackExtension(__DIR__.'/Resource/manifest.json', __DIR__.'/Resource');
        $this->assertEquals('fullpipe.extension.webpack', $extension->getName());
    }

    public function testGetFunctions()
    {
        $extension = new WebpackExtension(__DIR__.'/Resource/manifest.json', __DIR__.'/Resource');
        $parsers = $extension->getTokenParsers();

        $this->assertInstanceOf(EntryTokenParserJs::class, $parsers[0]);
        $this->assertInstanceOf(EntryTokenParserCss::class, $parsers[1]);
    }
}
