<?php

namespace xEdelweiss\SSG;

class Generator
{
    private $markdownParser;

    /**
     * Generator constructor.
     */
    public function __construct()
    {
        $this->markdownParser = new \Parsedown();
    }

    public function markdownToText($markdown)
    {
        return $this->markdownParser->text($markdown);
    }
}