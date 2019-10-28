<?php

namespace Fullpipe\TwigWebpackExtension\TokenParser;

class EntryTokenParserJs extends EntryTokenParser
{
    protected function type()
    {
        return 'js';
    }

    protected function generateHtml($entryPath, bool $defer)
    {
        $deferHtml = $defer === true ? 'defer' : '';
        return '<script type="text/javascript" src="' . $entryPath . '" '.$deferHtml.'></script>';
    }
}
