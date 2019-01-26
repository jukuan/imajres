<?php
/**
 * Created by PhpStorm.
 * User: julian
 * Date: 1/14/19
 * Time: 11:58 PM
 */

function is_cli()
{
    if (defined('STDIN')) {
        return true;
    }

    if (php_sapi_name() === 'cli') {
        return true;
    }

    if (array_key_exists('SHELL', $_ENV)) {
        return true;
    }

    if (empty($_SERVER['REMOTE_ADDR']) and !isset($_SERVER['HTTP_USER_AGENT']) and count($_SERVER['argv']) > 0) {
        return true;
    }

    if (!array_key_exists('REQUEST_METHOD', $_SERVER)) {
        return true;
    }

    return false;
}

function untrailingslashit($string)
{
    return rtrim($string, '/\\');
}

function trailingslashit($string)
{
    return untrailingslashit($string) . '/';
}

function merge_paths()
{
    $args = array_filter(func_get_args(), function ($path) {
        return !empty($path);
    });

    array_walk($args, function (&$path) {
        $path = untrailingslashit($path);
    });

    return implode('/', $args);
}
