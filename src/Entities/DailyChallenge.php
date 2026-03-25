<?php

declare(strict_types=1);

namespace App\Entities;

final class DailyChallenge
{
    public function __construct(
        public readonly int $id,
        public readonly string $date,
        public readonly int $characterId,
        public readonly string $mode
    ) {
    }
}
