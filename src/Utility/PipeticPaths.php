<?php

namespace Pipetic\Bundle\Utility;

class PipeticPaths
{
    protected const PATH_BUNDLE = '/Bundle';
    protected const PATH_RESOURCES = self::PATH_BUNDLE . '/Resources';
    protected const PATH_ASSETS = self::PATH_RESOURCES . '/assets';
    protected const PATH_IMAGES = self::PATH_ASSETS . '/images';

    public static function root(string $path = null): string
    {
        static $root;
        if ($root === null) {
            $root = realpath(dirname(__DIR__, 1));
        }
        return $root . $path;
    }

    public static function bundle(string $path = null): string
    {
        return self::root(self::PATH_BUNDLE) . $path;
    }

    public static function resources(string $path = null): string
    {
        return self::root(self::PATH_RESOURCES) . $path;
    }

    public static function images(string $path = null): string
    {
        return self::root(self::PATH_IMAGES) . $path;
    }

    public static function assets(string $path = null): string
    {
        return self::root(self::PATH_ASSETS) . $path;
    }

    public static function scripts(string $path = null): string
    {
        return self::assets('/scripts') . $path;
    }
}