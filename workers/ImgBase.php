<?php

/**
 * Created by PhpStorm.
 * User: julian
 * Date: 1/5/19
 * Time: 5:30 PM
 */
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
        return $pos;
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

    abstract public function processFile($filePath);

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

    public function run()
    {
        if (!$this->validate()) {
            Console::error($this->getLastError());

            return;
        }

        foreach ($this->getFiles() as $filePath) {
            $sizeBefore = $this->getFileSize($filePath);
            $this->processFile($filePath);
            $sizeAfter = $this->getFileSize($filePath);

            Console::info($filePath);
            Console::success(
                sprintf('Size before was %s and afrer %s', $sizeBefore, $sizeAfter)
            );
        }

        Console::info('===========================');

        Console::info(
            sprintf('Worker %s is done', get_called_class())
        );

        Console::info('==========================');
    }
}
