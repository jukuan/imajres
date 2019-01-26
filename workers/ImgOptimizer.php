<?php

namespace Imajres\Workers;

require_once 'ImgBase.php';

use Imajres\Entities\Console;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class ImgOptimizer extends ImgBase
{
    public function validate(): bool
    {
        if (parent::validate()) {
            return true;
        }

        Console::error('Not valid dirs');

        return false;
    }

    public function processFile(string $sourceFile, string $destination)
    {
        $optimizerChain = OptimizerChainFactory::create();

        $optimizerChain->optimize($sourceFile);
    }
}
