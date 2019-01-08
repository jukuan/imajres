<?php

/**
 * Created by PhpStorm.
 * User: julian
 * Date: 1/5/19
 * Time: 9:13 PM
 */
class MementoFile
{
    const LINE_DELIMITER = "\n";

    private $slug = '';
    private $lines = [];

    function __construct($key)
    {
        $this->slug = $key;

        $this->initFromMemory();
    }

    private function getFilePath()
    {
        return APP_PATH . '/memory/' . $this->slug . '.mem.txt';
    }

    private function encode($list)
    {
        $out = implode(self::LINE_DELIMITER, $list);

        return $out;
    }

    private function decode($out)
    {
        $list = explode(self::LINE_DELIMITER, $out);

        return $list;
    }

    private function initFromMemory()
    {
        $path = $this->getFilePath();
        if (file_exists($path)) {
            $out = file_get_contents($path);
            $this->lines = $this->decode($out);
        } else {
            file_put_contents($path, '');
        }
    }

    public function addToMemory($line)
    {
        $this->lines[] = $line;
        $path = $this->getFilePath();
        file_put_contents($path, $line . self::LINE_DELIMITER, FILE_APPEND);
    }

    public function has($line)
    {
        return in_array($line, $this->lines);
    }
}
