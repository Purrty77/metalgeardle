<?php

declare(strict_types=1);

use App\Core\Config;

$baseUrl = rtrim((string) (Config::get('app', 'base_url') ?? ''), '/');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Metal Gear Dle', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars(($baseUrl !== '' ? $baseUrl : '') . '/public/assets/css/app.css', ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
    <?php require $templatePath; ?>

    <footer class="site-footer">
        <div class="site-footer__inner">
            <div class="site-footer__column site-footer__column--brand">
                <p class="site-footer__eyebrow">Metal Gear Dle</p>
                <h2>Daily tactical guesses with a sharper, lighter finish.</h2>
                <p>
                    Follow the project, share feedback, or keep building the roster on GitHub.
                </p>
            </div>

            <div class="site-footer__column site-footer__column--links">
                <h3>Project Info</h3>
                <p>
                    Essential browser storage is used for daily gameplay only. Read the full policy for the current setup and how this should evolve if ads or analytics are added later.
                </p>
                <nav class="site-footer__nav" aria-label="Footer">
                    <a href="https://github.com/purrty77" target="_blank" rel="noreferrer">
                        <span class="github-link-mark" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M12 2C6.48 2 2 6.59 2 12.24c0 4.51 2.87 8.34 6.84 9.69.5.09.66-.22.66-.49 0-.24-.01-1.04-.01-1.88-2.78.62-3.37-1.22-3.37-1.22-.45-1.18-1.11-1.49-1.11-1.49-.91-.64.07-.63.07-.63 1 .07 1.53 1.06 1.53 1.06.9 1.57 2.35 1.12 2.92.86.09-.67.35-1.12.63-1.38-2.22-.26-4.55-1.14-4.55-5.08 0-1.12.39-2.03 1.03-2.75-.1-.26-.45-1.31.1-2.73 0 0 .84-.27 2.75 1.05A9.31 9.31 0 0 1 12 6.84c.85 0 1.71.12 2.51.36 1.91-1.32 2.75-1.05 2.75-1.05.55 1.42.2 2.47.1 2.73.64.72 1.03 1.63 1.03 2.75 0 3.95-2.34 4.82-4.57 5.07.36.32.68.95.68 1.92 0 1.39-.01 2.5-.01 2.84 0 .27.18.59.67.49A10.25 10.25 0 0 0 22 12.24C22 6.59 17.52 2 12 2Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <span>GitHub</span>
                    </a>
                    <a href="<?= htmlspecialchars($baseUrl !== '' ? $baseUrl . '/privacy-policy' : '/privacy-policy', ENT_QUOTES, 'UTF-8') ?>">Privacy Policy</a>
                </nav>
                <p class="site-footer__credit">Background artwork credit: Dragoth from the <a href="https://forums.spacebattles.com/" target="_blank" rel="noreferrer">SpaceBattles.com</a> forum.</p>
            </div>
        </div>
    </footer>
</body>
</html>
