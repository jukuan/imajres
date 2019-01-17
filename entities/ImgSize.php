<?php

namespace Imajres\Entities;

class ImgSize
{
    protected $name = '';
    protected $path = '';
    protected $size = '';
    protected $width = 0;
    protected $height = 0;
    protected $quality = 5;

    public function __construct($params = [])
    {
        foreach ($params as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }

    public function getParsedSizes()
    {
        if (!$this->width || !$this->height) {
            $this->size = strtolower($this->size);
            list($this->width, $this->height) = explode('x', $this->size);
        }

        return [
            $this->width,
            $this->height
        ];
    }

    public function getOutputDir()
    {
        return $this->path;
    }
}
