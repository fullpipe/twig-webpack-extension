<?php

namespace Fullpipe\TwigWebpackExtension\TokenParser;

class EntryTokenParserJs extends EntryTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function type(): string
    {
        return 'js';
    }

    /**
     * {@inheritdoc}
     */
    protected function generateHtml(string $entryPath): string
    {
        return '<script type="text/javascript" src="'.$entryPath.'"></script>';
    }
}
