<?php

declare(strict_types=1);

$host = (string) ($_SERVER['HTTP_HOST'] ?? '');
$isLocal = str_contains($host, 'localhost') || str_contains($host, '127.0.0.1');

return [
    'app_name' => 'Metal Gear Dle',
    'base_url' => $isLocal ? '/metalgeardle' : '',
    'timezone' => 'Europe/Paris',
    'daily_reset_hour' => 6,
];
