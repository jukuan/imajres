<?php

namespace Imajres\Workers;

require_once 'ImgResizer.php';
require_once __DIR__ . '/../inc/functions.php';

if (!defined('APP_PATH')) {
    define('APP_PATH', dirname(dirname(__DIR__)));
}

use Imajres\Entities\Console;
use Imajres\Entities\ImgSpecial;

class ImgResizerSpecial extends ImgResizer
{
    private $currentSize = [];

    private function getOptionImages()
    {
        $list = [];

        foreach ($this->getOption('images') as $filename) {
            $list[] = merge_paths($this->source, $filename);
        }

        return $list;
    }

    public function processFile(string $sourceFile, string $destination)
    {
        list($width, $height) = $this->currentSize;
        $filename = is_file($sourceFile) ? basename($sourceFile) : false;
        if (!$filename || !$width || !$height) {
            return false;
        }

        $this->resizeImage($sourceFile, $destination, $width, $height);
    }

    public function run(): void
    {
        foreach ($this->getOptionImages() as $specialString) {
            $sizer = new ImgSpecial($specialString);
            if (!$sizer->isValid()) {
                Console::warning(\sprintf('Not valid string: %s', $specialString));
                continue;
            }
            $this->currentSize = $sizer->getParsedSize();

            Console::info($sizer->getPath());
            Console::info(\sprintf('New size is %d x %d.', $sizer->getWidth(), $sizer->getHeight()));
            $this->processFile($sizer->getPath(), $sizer->getPath());
        }
    }
}
