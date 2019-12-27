<?php

namespace Fullpipe\TwigWebpackExtension\TokenParser;

class EntryTokenParserCss extends EntryTokenParser
{
    /**
     * {@inheritdoc}
     */
    protected function type(): string
    {
        return 'css';
    }

    /**
     * {@inheritdoc}
     */
    protected function generateHtml(string $entryPath): string
    {
        return '<link type="text/css" href="'.$entryPath.'" rel="stylesheet">';
    }
}
