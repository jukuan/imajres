<?php

require_once 'ImgBase.php';

/**
 * Created by PhpStorm.
 * User: julian
 * Date: 1/5/19
 * Time: 12:49 AM
 */
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

        $imagick = new \Imagick(realpath($imagePath));

        $imagick->resizeImage($width, $height, $filterType, $blur);

        $cropWidth = $imagick->getImageWidth();
        $cropHeight = $imagick->getImageHeight();

        if ($cropZoom) {
            $newWidth = $cropWidth / 2;
            $newHeight = $cropHeight / 2;

            $imagick->cropimage(
                $newWidth,
                $newHeight,
                ($cropWidth - $newWidth) / 2,
                ($cropHeight - $newHeight) / 2
            );

            $imagick->scaleimage(
                $imagick->getImageWidth() * 4,
                $imagick->getImageHeight() * 4
            );
        }

//        return $imagick->getImageBlob();

        $ext = pathinfo($imagePath, PATHINFO_EXTENSION);
        $imagick->setImageFormat($ext);
        file_put_contents(APP_PATH . DIRECTORY_SEPARATOR . $outputPath, $imagick);

//        $imagick->imageWriteFile(fopen($outputPath, 'wb'));
    }

    public function getOptionSizes()
    {
        return $this->getOption('sizes');
    }

    public function processFile($filePath)
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
