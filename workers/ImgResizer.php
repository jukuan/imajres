<?php

namespace Imajres\Workers;

require_once 'ImgBase.php';

if (!defined('APP_PATH')) {
    define('APP_PATH', dirname(dirname(__DIR__)));
}

use Imagick;
use Imajres\Entities\ImgSize;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class ImgResizer extends ImgBase
{
    /**
     * Resizer.
     * TODO: refactor this method
     *
     * @param string $imagePath
     * @param string $outputPath
     * @param int $width
     * @param int $height
     *
     * @param bool $cropZoom
     */
    public function resizeImage(string $imagePath, string $outputPath, int $width, int $height, bool $cropZoom = false)
    {
        //The blur factor where &gt; 1 is blurry, &lt; 1 is sharp.
        $blur = 1;

        $filterType = Imagick::FILTER_LANCZOS;

        $imagick = new Imagick(realpath($imagePath));

        $imagick->resizeImage($width, $height, $filterType, $blur);

        $cropWidth = $imagick->getImageWidth();
        $cropHeight = $imagick->getImageHeight();

        if ($cropZoom) {
            $newWidth = intval($cropWidth / 2);
            $newHeight = intval($cropHeight / 2);

            $imagick->cropimage(
                $newWidth,
                $newHeight,
                intval(($cropWidth - $newWidth) / 2),
                intval(($cropHeight - $newHeight) / 2)
            );

            $imagick->scaleimage(
                $imagick->getImageWidth() * 4,
                $imagick->getImageHeight() * 4
            );
        }

        $ext = pathinfo($imagePath, PATHINFO_EXTENSION);
        $imagick->setImageFormat($ext);
        file_put_contents(APP_PATH . DIRECTORY_SEPARATOR . $outputPath, $imagick);

//        $imagick->imageWriteFile(fopen($outputPath, 'wb'));
    }

    public function getOptionSizes()
    {
        return $this->getOption('sizes');
    }

    public function processFile(string $filePath)
    {
        $filename = is_file($filePath) ? basename($filePath) : false;
        if (!$filename) {
            return false;
        }

        foreach ($this->getOptionSizes() as $imgSize) {
            $sizer = new ImgSize($imgSize);
            $output = $sizer->getOutputDir() . $filename;
            list($width, $height) = $sizer->getParsedSizes();
            $this->resizeImage($filePath, $output, $width, $height);
        }
    }
}
