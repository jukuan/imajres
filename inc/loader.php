<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 1/5/19
 * Time: 12:22 AM
 */


require_once __DIR__ . '/../vendor/autoload.php';
require_once 'functions.php';
require_once 'FilesInspector.php';

if (!is_cli()) {
    die('You should run it in command line mode only!');
}

$directories = [
    __DIR__ . '/../workers',
    __DIR__ . '/../entities',
];

array_walk($directories, function ($dir) {
    (new FilesInspector($dir))->execute(function ($file) {
        require_once $file;
    });
});
