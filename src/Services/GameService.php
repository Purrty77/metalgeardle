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
            'first_game' => $this->matchOrderedGame($guess->firstGame, $solution->firstGame),
            'era' => $this->matchOrderedYear($guess->era, $solution->era),
            'role_type' => $this->match($guess->roleType, $solution->roleType),
        ];
    }

    private function match(string $left, string $right): string
    {
        return mb_strtolower(trim($left)) === mb_strtolower(trim($right)) ? 'correct' : 'incorrect';
    }

    private function matchOrderedYear(string $guessYear, string $solutionYear): string
    {
        if ($this->match($guessYear, $solutionYear) === 'correct') {
            return 'correct';
        }

        $guess = (int) trim($guessYear);
        $solution = (int) trim($solutionYear);

        if ($guess === 0 || $solution === 0) {
            return 'incorrect';
        }

        return $guess < $solution ? 'higher' : 'lower';
    }

    private function matchOrderedGame(string $guessGame, string $solutionGame): string
    {
        if ($this->match($guessGame, $solutionGame) === 'correct') {
            return 'correct';
        }

        $gameOrder = [
            'metal gear' => 1,
            'metal gear 2: solid snake' => 2,
            'metal gear solid' => 3,
            'metal gear solid 2: sons of liberty' => 4,
            'metal gear solid 3: snake eater' => 5,
            'metal gear solid 4: guns of the patriots' => 6,
            'metal gear solid: peace walker' => 7,
            'metal gear solid v: ground zeroes' => 8,
            'metal gear solid v: the phantom pain' => 9,
        ];

        $guess = $gameOrder[mb_strtolower(trim($guessGame))] ?? null;
        $solution = $gameOrder[mb_strtolower(trim($solutionGame))] ?? null;

        if ($guess === null || $solution === null) {
            return 'incorrect';
        }

        return $guess < $solution ? 'higher' : 'lower';
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
