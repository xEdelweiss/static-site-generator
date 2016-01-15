<?php

namespace xEdelweiss\SSG;

use RecursiveArrayIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Yaml\Parser;

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
     * @param $dir
     */
    public function generateRawCache($dir)
    {
        /** @var \SplFileInfo[] $objects */
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS));
        $data = [];

        foreach($objects as $name => $object){
            if ($object->getExtension() != 'md') {
                continue;
            }

            $normalizedPathname = $this->normalizePathname($dir, $object);
            $pageContent = $this->getPageContent($object);
            $pageData = $this->extractDataFromPage($pageContent);

            $data = array_merge_recursive($data, [
                $normalizedPathname => $pageData,
            ]);

            echo "$normalizedPathname - $name\n";
        }

        $this->data = $this->arrayToDot($data);

        var_dump($this->data);
    }

    /**
     * @param $markdown
     * @return string
     */
    public function markdownToText($markdown)
    {
        return $this->markdownParser->text($markdown);
    }

    /**
     * @param $dir
     * @param $object
     * @return mixed
     */
    protected function normalizePathname($dir, $object)
    {
        $normalizedPathname = preg_replace("/{$dir}\\/(.*)\\.md/", '$1', $object->getPathname());
        $normalizedPathname = str_replace('/', '.', $normalizedPathname);

        return $normalizedPathname;
    }

    /**
     * @param $object
     * @return string
     */
    protected function getPageContent($object)
    {
        return file_get_contents($object);
    }

    /**
     * @param $content
     *
     * @return array
     */
    protected function extractDataFromPage($content)
    {
        $dataText = substr($content, 0, strpos($content, '---'));
        $yaml = new Parser();
        $data = $yaml->parse($dataText);

        return $data;
    }

    /**
     * @param $array
     * @return array
     */
    protected function arrayToDot($array)
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
        $result = [];
        
        foreach ($iterator as $leafValue) {
            $keys = array();
            foreach (range(0, $iterator->getDepth()) as $depth) {
                $keys[] = $iterator->getSubIterator($depth)->key();
            }
            $result[ join('.', $keys) ] = $leafValue;
        }

        return $result;
    }
}