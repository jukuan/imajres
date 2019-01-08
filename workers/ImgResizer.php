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
    function resizeImage($imagePath, $width, $height, $cropZoom = false)
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
        $imagick->writeImage($imagePath);
    }

    public function processFile($filePath) {
        $this->resizeImage($filePath, $width, $height);
    }
}

