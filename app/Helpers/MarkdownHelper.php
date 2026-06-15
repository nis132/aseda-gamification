<?php

namespace App\Helpers;

use Parsedown;

class MarkdownHelper
{
    protected static ?Parsedown $instance = null;

    public static function render(string $text): string
    {
        if (!self::$instance) {
            self::$instance = new Parsedown();
            self::$instance->setSafeMode(true); // cegah XSS
        }
        return self::$instance->text($text);
    }
}