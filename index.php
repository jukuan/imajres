<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 1/3/19
 * Time: 11:19 PM
 */

define('APP_PATH', __DIR__);
require_once 'inc/loader.php';

use Imajres\Workers\ImgOptimizer;
use Imajres\Workers\ImgResizer;
use Noodlehaus\Config;
use Noodlehaus\Parser\Json;

$conf = new Config('config.json');

$source = $conf->get('source');
$sizes = $conf->get('sizes');

(new ImgOptimizer($source))->run();
(new ImgResizer($source, $source, ['sizes' => $sizes]))->run();
