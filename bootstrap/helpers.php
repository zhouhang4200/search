<?php

if (!function_exists('myLog')) {
    function myLog($fileName, $data = [])
    {
        if (php_sapi_name() == 'cli') {
            $fileName = $fileName . '-cli';
        }
        $log = new \Monolog\Logger($fileName);
        $log->pushHandler(new \Monolog\Handler\StreamHandler(storage_path() . '/logs/' . $fileName . '-' . date('Y-m-d') . '.log'));
        $log->addInfo($fileName, $data);
    }
}
