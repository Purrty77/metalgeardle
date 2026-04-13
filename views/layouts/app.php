<?php

declare(strict_types=1);

use App\Core\Config;

$baseUrl = rtrim((string) (Config::get('app', 'base_url') ?? ''), '/');
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = (string) ($_SERVER['HTTP_HOST'] ?? 'metalgeardle.com');
$requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
$currentUrl = $scheme . '://' . $host . $requestUri;
$pageTitle = (string) ($title ?? 'Metal Gear Dle');
$pageDescription = (string) ($metaDescription ?? 'Guess the Metal Gear character of the day in a LoLdle-style browser game with daily resets, streaks, and tactical attribute comparisons.');
$pageCanonical = (string) ($canonicalUrl ?? $currentUrl);
$pageRobots = (string) ($metaRobots ?? 'index,follow');
$defaultSocialImage = $scheme . '://' . $host . (($baseUrl !== '' ? $baseUrl : '') . '/public/assets/img/social-card-home.png');
$pageImage = (string) ($ogImageUrl ?? $defaultSocialImage);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="robots" content="<?= htmlspecialchars($pageRobots, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="canonical" href="<?= htmlspecialchars($pageCanonical, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($pageCanonical, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:site_name" content="Metal Gear Dle">
    <meta property="og:image" content="<?= htmlspecialchars($pageImage, ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($pageImage, ENT_QUOTES, 'UTF-8') ?>">
    <link rel="icon" type="image/png" sizes="48x48" href="<?= htmlspecialchars(($baseUrl !== "" ? $baseUrl : "") . '/public/assets/img/favicon-48x48.png', ENT_QUOTES, 'UTF-8') ?>">
    <link rel="shortcut icon" href="<?= htmlspecialchars(($baseUrl !== "" ? $baseUrl : "") . '/public/assets/img/favicon-48x48.png', ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(($baseUrl !== '' ? $baseUrl : '') . '/public/assets/css/app.css', ENT_QUOTES, 'UTF-8') ?>">
</head>
<body>
    <?php require $templatePath; ?>

    <footer class="site-footer">
        <div class="site-footer__inner">
            <div class="site-footer__column site-footer__column--brand">
                <p class="site-footer__eyebrow">Transmission Archive</p>
                <h3>Support the Project</h3>
                <p>
                    Tips are optional and go only toward paying the hosting costs for Metal Gear Dle.
                </p>
                <p class="site-footer__repo-link">
                    <a href="https://ko-fi.com/purrty77" target="_blank" rel="noreferrer">
                        <span class="kofi-link-mark" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M4 6.5A1.5 1.5 0 0 1 5.5 5h9A1.5 1.5 0 0 1 16 6.5V7h1.5A3.5 3.5 0 0 1 21 10.5c0 1.76-1.3 3.22-3 3.46A5 5 0 0 1 13 18H8a5 5 0 0 1-5-5V6.5Zm12 2V11a2 2 0 0 0 0 .24 1.99 1.99 0 0 0 0 .27c.86-.22 1.5-1 1.5-1.94A1.5 1.5 0 0 0 16 8.5Z" fill="currentColor"/>
                                <path d="M12 9.15c.46-.75 1.9-.63 1.9.65 0 1.53-1.9 3.15-1.9 3.15s-1.9-1.62-1.9-3.15c0-1.28 1.44-1.4 1.9-.65Z" fill="#0d1614"/>
                            </svg>
                        </span>
                        <span>Support the hosting on Ko-fi</span>
                    </a>
                </p>
                <p class="site-footer__repo-link">
                    <a href="https://github.com/purrty77" target="_blank" rel="noreferrer">
                        <span class="github-link-mark" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M12 2C6.48 2 2 6.59 2 12.24c0 4.51 2.87 8.34 6.84 9.69.5.09.66-.22.66-.49 0-.24-.01-1.04-.01-1.88-2.78.62-3.37-1.22-3.37-1.22-.45-1.18-1.11-1.49-1.11-1.49-.91-.64.07-.63.07-.63 1 .07 1.53 1.06 1.53 1.06.9 1.57 2.35 1.12 2.92.86.09-.67.35-1.12.63-1.38-2.22-.26-4.55-1.14-4.55-5.08 0-1.12.39-2.03 1.03-2.75-.1-.26-.45-1.31.1-2.73 0 0 .84-.27 2.75 1.05A9.31 9.31 0 0 1 12 6.84c.85 0 1.71.12 2.51.36 1.91-1.32 2.75-1.05 2.75-1.05.55 1.42.2 2.47.1 2.73.64.72 1.03 1.63 1.03 2.75 0 3.95-2.34 4.82-4.57 5.07.36.32.68.95.68 1.92 0 1.39-.01 2.5-.01 2.84 0 .27.18.59.67.49A10.25 10.25 0 0 0 22 12.24C22 6.59 17.52 2 12 2Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <span>GitHub</span>
                    </a>
                </p>
            </div>

            <div class="site-footer__column site-footer__column--links">
                <h3>Mission Data</h3>
                <p>
                    Patch notes, feedback, and privacy controls for the live build.
                </p>
                <nav class="site-footer__nav" aria-label="Footer">
                    <button type="button" class="site-footer__button" id="updates-modal-button">
                        <span class="github-link-mark" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M6 5h12v2H6V5Zm0 6h12v2H6v-2Zm0 6h8v2H6v-2Zm11-1.5 1.6 1.6 3.4-3.4 1.4 1.4-4.8 4.8-3-3 1.4-1.4Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <span>Patch Notes</span>
                    </button>
                    <button type="button" class="site-footer__button site-footer__button--highlight" id="suggestion-modal-button">
                        <span class="github-link-mark" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false">
                                <path d="M4 5h16v11H7.5L4 19.5V5Zm2 2v7.67L6.67 14H18V7H6Zm2 2h8v2H8V9Zm0 4h5v2H8v-2Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <span>Send Suggestion</span>
                    </button>
                    <a href="<?= htmlspecialchars($baseUrl !== '' ? $baseUrl . '/privacy-policy' : '/privacy-policy', ENT_QUOTES, 'UTF-8') ?>">Privacy Policy</a>
                </nav>
            </div>
        </div>
    </footer>
</body>
</html>
