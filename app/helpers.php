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

if (!function_exists("constants")) {
    function constants($key)
    {
        return config('constants.' . $key);
    }
}
