<?php

namespace Fullpipe\TwigWebpackExtension\TokenParser;

class EntryTokenParserCss extends EntryTokenParser
{
    protected function type()
    {
        return 'css';
    }

    protected function generateHtml($entryPath)
    {
        return '<link type="text/css" href="' . $entryPath . '" rel="stylesheet">';
    }
}
