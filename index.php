<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 1/3/19
 * Time: 11:19 PM
 */

define('APP_PATH', __DIR__);
require_once 'inc/loader.php';

use Noodlehaus\Config;
use Noodlehaus\Parser\Json;

$conf = new Config('config.json');

$source = $conf->get('source');
$sizes = $conf->get('sizes');

//(new ImgOptimizer($source))->run();
(new ImgResizer($source, $source, ['sizes' => $sizes]))->run();

/*
$files = (new FilesInspector($source))->getFilesList();

if ($sizes && $files) {
    foreach ($sizes as $imgSize) {
        $imgSettings = new ImgSize($imgSize);
        foreach ($files as $filePath) {
            $optimizerChain = OptimizerChainFactory::create();

            $optimizerChain->optimize($filePath);
            var_dump($filePath);
            die();
        }
    }
}*/
