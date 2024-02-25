<?php

namespace Pipetic\Bundle\Utility;

class PipeticAssets
{
    public static function scriptInline(string $path): string
    {
        $path = PipeticPaths::scripts($path);

        return '<script type="text/javascript">' . file_get_contents($path) . '</script>';
    }
}