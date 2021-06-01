<?php

if (!function_exists("ProgressBar")) {
    function ProgressBar($percentage = 50, $style = 2)
    {
        $barSize = 20;
        $styles = [
            [
                "remaining" => '░',
                "completed" => '▓'
            ],
            [
                "remaining" => '▤',
                "completed" => '▦'
            ],
            [
                "remaining" => '▯',
                "completed" => '▮'
            ]
        ];

        $CompletedCount = floor($barSize * $percentage / 100);

        $here = $barSize - $CompletedCount;

        $progress = str_repeat($styles[$style]['completed'], $CompletedCount) . str_repeat($styles[$style]['remaining'], $here) . ' ' . round($percentage, 2) . '%';

        return $progress;
    }
}

if (!function_exists("setItem")) {
    function setItem($key, $value)
    {
        $store_data = [
            $key => $value
        ];

        $storage = json_encode($store_data);

        file_put_contents('storage.txt', $storage);
    }
}

if (!function_exists("getItem")) {
    function getItem($value)
    {
        if (file_exists('storage.txt')) {
            $Settings = file_get_contents('storage.txt');
            $json = json_decode($Settings);
            return $json->$value;
        }

        return "'storage.txt' file doesn't exist";
    }
}

if (!function_exists("clearItem")) {
    function clearItem()
    {
        unlink('storage.txt');
    }
}

if (!function_exists("constants")) {
    function constants($key)
    {
        return config('constants.' . $key);
    }
}
