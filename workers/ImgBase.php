<?php

namespace Imajres\Workers;

use Imajres\Entities\Console;
use Imajres\Entities\MementoFile;
use Imajres\FilesInspector;

require_once '../inc/functions.php';

if (!defined('APP_PATH')) {
    define('APP_PATH', dirname(dirname(__DIR__)));
}

abstract class ImgBase
{
    protected $source = '';
    protected $output = '';
    protected $options = [];
    protected $errors = [];
    protected $memento = null;

    private function getClassName()
    {
        $className = get_called_class();
        if ($pos = strrpos($className, '\\')) {
            return substr($className, $pos + 1);
        }
        return $className;
    }

    public function __construct(string $source, string $output = null, array $options = [])
    {
        $this->source = $source;
        $this->output = $output ?? $source;
        $this->options = $options;

        $this->memento = new MementoFile($this->getClassName());
    }

    public function isValidDir(string $dir):bool
    {
        return file_exists($dir) && is_writable($dir);
    }

    public function validate(): bool
    {
        if (!$this->isValidDir($this->source)) {
            $this->addError(
                sprintf('Source directory "%s" is not valid.', $this->source)
            );
        }

        if (!$this->isValidDir($this->output)) {
            $this->addError(
                sprintf('Output directory "%s" is not valid.', $this->output)
            );
        }

        return 0 === count($this->errors);
    }

    protected function getOption(string $key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }

    protected function hasOption(string $key)
    {
        $val = $this->getOption($key);

        return null !== $val && false !== $val;
    }

    protected function addError($msg)
    {
        $this->errors[] = 'Error: ' . $msg;
    }

    protected function getLastError()
    {
        return end($this->errors);
    }

    public function getFiles()
    {
        return (new FilesInspector($this->source))->getFilesList();
    }

    abstract public function processFile(string $sourceFile, string $destination);

    protected function isProcessed(string $file): bool
    {
        return $this->memento->has($file);
    }

    protected function addProcessed(string $file)
    {
        $this->memento->addToMemory($file);
    }

    protected function getFileSize(string $path): int
    {
        return filesize($path);
    }

    protected function preparePath(string $path): string
    {
        if (false !== \strpos($path, APP_PATH)) {
            return $path;
        }

        return merge_paths(APP_PATH, $path);
    }

    protected function prepareDestination(string $sourceFile): string
    {
        if (!$this->output) {
            return $sourceFile;
        }

        $filename = basename($sourceFile);

        return trailingslashit($this->output) . $filename;
    }

    public function afterRun(): void
    {
        Console::info('===========================');

        Console::info(
            sprintf('Worker %s is done', get_called_class())
        );

        Console::info('==========================');
    }

    public function run(): void
    {
        if (!$this->validate()) {
            Console::error($this->getLastError());

            return;
        }

        foreach ($this->getFiles() as $sourceFile) {
            Console::info($sourceFile);

            $sizeBefore = $this->getFileSize($sourceFile);

            $destinationFile = $this->prepareDestination($sourceFile);
            $this->processFile($sourceFile, $destinationFile);

            $sizeAfter = $this->getFileSize($destinationFile);

            $ratio = number_format($sizeAfter/$sizeBefore*100, 2);
            Console::success(
                sprintf('Size before was %d and size after is %d. Ratio: %d.', $sizeBefore, $sizeAfter, $ratio)
            );
        }

        $this->afterRun();
    }
}
