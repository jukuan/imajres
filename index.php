<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 1/3/19
 * Time: 11:19 PM
 */

use Imajres\Workers\ImgOptimizer;
use Imajres\Workers\ImgResizer;
use Imajres\Workers\ImgResizerSpecial;
use Noodlehaus\Config;

define('APP_PATH', __DIR__);
require_once 'inc/loader.php';

$config = merge_paths(APP_PATH, 'config.json');
if (!file_exists($config)) {
    \Imajres\Entities\Console::error('Config file does not found.');
    die();
}
$theConf = new Config($config);

if ($config = (object) $theConf->get('optimizer')) {
    (new ImgOptimizer($config->source))->run();
}

if ($config = (object) $theConf->get('resizer')) {
    if (isset($config->sizes)) {
        (new ImgResizer($config->source, null, ['sizes' => $config->sizes]))->run();
    }

    if (isset($config->images)) {
        (new ImgResizerSpecial($config->source, null, ['images' => $config->images]))->run();
    }
}
