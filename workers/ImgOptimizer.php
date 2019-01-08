<?php

require_once 'ImgBase.php';

/**
 * Created by PhpStorm.
 * User: julian
 * Date: 1/5/19
 * Time: 12:49 AM
 */
use Spatie\ImageOptimizer\OptimizerChainFactory;

class ImgOptimizer extends ImgBase
{
    public function validate()
    {
        // TODO: Implement validate() method.
    }

    public function processFile($filePath)
    {
        $optimizerChain = OptimizerChainFactory::create();

        $optimizerChain->optimize($filePath);
    }
}

