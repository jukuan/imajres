<?php

/**
 * Created by PhpStorm.
 * User: julian
 * Date: 1/3/19
 * Time: 11:45 PM
 */
class FilesInspector
{
    protected $dir;

    function __construct($dir)
    {
        $this->dir = $dir;
    }

    public function getFilesList()
    {
        $list = [];
        if ($handle = opendir($this->dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry === "." || $entry === "..") {
                    continue;
                }

                $list[] = $this->dir . '/' . $entry;
            }
            closedir($handle);
        }

        return $list;
    }

    public function execute(callable $callback)
    {
        $list = $this->getFilesList();

        array_walk($list, $callback);
    }
}
