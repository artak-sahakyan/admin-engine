<?php

namespace common\helpers;

class SitemapHelper
{
    /**
     * @var resource
     */
    protected $fileHandle;

    /**
     * Insert vars into template.
     * @param string $template
     * @param array $set
     * @return string
     */
    public function makeItem(string $template, array $set = []) : string
    {
        preg_match_all('/{{(.+?)}}/', $template, $matches);
        foreach ($matches[1] as $index => $key) {
            if (is_null($set[$key])) {
                // remove item
                $template = preg_replace('/\s*<' . $key . '>{{' . $key . '}}<\/' . $key . '>/', '', $template);
            } else {
                // set value
                $value = $set[$key];
                $template = str_replace($matches[0][$index], $value, $template);
            }
        }

        return $template;
    }

    /**
     * @param string $catalogPath
     * @param int|null $page
     * @param string $fileName
     * @return string
     */
    public function makePath(string $catalogPath, int $page = null, string $fileName) : string
    {
        $fileName = $fileName . ((is_integer($page)) ? ('_' . $page) : '') . '.xml';
        $sitemapPath = $catalogPath . '/' . $fileName;

        return $sitemapPath;
    }

    /**
     * @param string $filePath
     */
    public function fileOpen(string $filePath)
    {
        $dirPath = dirname($filePath);
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $this->fileHandle = fopen($filePath, 'w');

        if ($this->fileHandle === false) {
            throw new \RuntimeException('Could not open file "' . $filePath . '"');
        }

    }

    /**
     * @param string $data
     */
    public function fileWrite(string $data)
    {
        fwrite($this->fileHandle, $data);
    }

    public function fileClose()
    {
        fclose($this->fileHandle);
    }

    public function fileChmod(string $filePath, $perms = 0777)
    {
        chmod($filePath, $perms);
    }
}