<?php

declare(strict_types=1);

namespace App\Services;

use App\Entities\Character;

final class GameService
{
    /**
     * @return array<string, string>
     */
    public function compare(Character $guess, Character $solution): array
    {
        return [
            'name' => $guess->id === $solution->id ? 'correct' : 'incorrect',
            'gender' => $this->match($guess->gender, $solution->gender),
            'affiliation' => $this->matchMultiValue($guess->affiliation, $solution->affiliation),
            'nationality' => $this->matchMultiValue($guess->nationality, $solution->nationality),
            'first_game' => $this->match($guess->firstGame, $solution->firstGame),
            'era' => $this->match($guess->era, $solution->era),
            'role_type' => $this->match($guess->roleType, $solution->roleType),
        ];
    }

    private function match(string $left, string $right): string
    {
        return mb_strtolower(trim($left)) === mb_strtolower(trim($right)) ? 'correct' : 'incorrect';
    }

    private function matchMultiValue(string $left, string $right): string
    {
        $leftValues = $this->normalizeValues($left);
        $rightValues = $this->normalizeValues($right);

        if ($leftValues === $rightValues) {
            return 'correct';
        }

        if (count(array_intersect($leftValues, $rightValues)) > 0) {
            return 'close';
        }

        return 'incorrect';
    }

    /**
     * @return string[]
     */
    private function normalizeValues(string $value): array
    {
        $parts = array_map(
            static fn (string $part): string => mb_strtolower(trim($part)),
            explode('|', $value)
        );

        $parts = array_values(array_filter($parts, static fn (string $part): bool => $part !== ''));
        sort($parts);

        return array_values(array_unique($parts));
    }
}
