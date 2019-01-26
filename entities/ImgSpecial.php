<?php

namespace Imajres\Entities;

class ImgSpecial
{
    protected $path = '';
    protected $filename = '';
    protected $size = [];

    public function __construct(string $path)
    {
        list($this->path, $size) = explode('#', $path);
        $this->size = explode('x', $size);

        $this->filename = basename($path);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getParsedSize()
    {
        return $this->size;
    }

    public function getWidth()
    {
        return $this->size[0];
    }

    public function getHeight()
    {
        return $this->size[1];
    }

    public function isValid()
    {
        return !empty($this->filename) && $this->getWidth() && $this->getHeight();
    }
}
