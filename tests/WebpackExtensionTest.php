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
        $extension = new WebpackExtension('manifest.json');
        $this->assertEquals('fullpipe.extension.webpack', $extension->getName());
    }

    public function testGetFunctions()
    {
        $extension = new WebpackExtension('manifest.json');
        $parsers = $extension->getTokenParsers();

        $this->assertInstanceOf(EntryTokenParserJs::class, $parsers[0]);
        $this->assertInstanceOf(EntryTokenParserCss::class, $parsers[1]);
    }
}
