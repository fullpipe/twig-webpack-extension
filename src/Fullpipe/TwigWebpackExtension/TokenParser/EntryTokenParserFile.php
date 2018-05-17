<?php

namespace Fullpipe\TwigWebpackExtension\TokenParser;

class EntryTokenParserCss extends EntryTokenParser
{
    protected function type()
    {
        return 'file';
    }

    protected function generateHtml($entryPath)
    {
        return $entryPath;
    }
}
