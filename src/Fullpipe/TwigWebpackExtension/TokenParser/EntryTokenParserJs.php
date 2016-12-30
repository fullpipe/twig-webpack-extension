<?php

namespace Fullpipe\TwigWebpackExtension\TokenParser;

class EntryTokenParserJs extends EntryTokenParser
{
    protected function type()
    {
        return 'js';
    }

    protected function generateHtml($entryPath)
    {
        return '<script type="text/javascript" src="' . $entryPath . '"></script>';
    }
}
