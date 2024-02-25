<?php

namespace  Pipetic\Bundle\Bundle\Library\View;

class ViewUtility
{
    public const NAMESPACE = 'PipeticBundle';
    public static function registerViewPaths($view, $module = null): void
    {
        $modules = [
            $module,
            'base',
        ];
        foreach ($modules as $module) {
            $path = realpath(__DIR__ . '/../../Resources/views/' . $module);
            $view->addPath($path);
            $view->addPath($path, self::NAMESPACE);
        }
    }
}
