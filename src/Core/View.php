<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    /**
     * @param array<string, mixed> $data
     */
    public static function render(string $template, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $templatePath = dirname(__DIR__, 2) . '/views/' . $template . '.php';

        if (!is_file($templatePath)) {
            throw new \RuntimeException(sprintf('View "%s" not found.', $template));
        }

        require dirname(__DIR__, 2) . '/views/layouts/app.php';
    }
}
