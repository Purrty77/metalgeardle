<?php

declare(strict_types=1);

namespace App\Core;

final class Config
{
    /**
     * @var array<string, array<string, mixed>>
     */
    private static array $items = [];

    /**
     * @return mixed
     */
    public static function get(string $file, ?string $key = null)
    {
        if (!isset(self::$items[$file])) {
            $path = dirname(__DIR__, 2) . '/config/' . $file . '.php';

            if (!is_file($path)) {
                throw new \RuntimeException(sprintf('Config file "%s" not found.', $file));
            }

            self::$items[$file] = require $path;
        }

        if ($key === null) {
            return self::$items[$file];
        }

        return self::$items[$file][$key] ?? null;
    }
}
