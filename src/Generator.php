<?php

namespace xEdelweiss\SSG;

class Generator
{
    protected $data = [];

    /** @var \Parsedown */
    private $markdownParser;

    /**
     * Generator constructor.
     */
    public function __construct()
    {
        $this->markdownParser = new \Parsedown();
    }

    /**
     * @param $markdown
     * @return string
     */
    public function markdownToText($markdown)
    {
        return $this->markdownParser->text($markdown);
    }
}