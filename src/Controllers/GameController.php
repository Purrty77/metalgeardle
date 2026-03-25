<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Config;
use App\Core\View;
use App\Repositories\CharacterRepository;
use App\Repositories\DailyChallengeRepository;
use App\Services\GameService;
use Throwable;

final class GameController
{
    public function index(): void
    {
        $error = null;
        $challenge = null;
        $characters = [];
        $dailySolveCount = 0;
        $yesterdayCharacterName = null;
        $baseUrl = (string) (Config::get('app', 'base_url') ?? '');

        try {
            $dailyChallenges = new DailyChallengeRepository();
            $charactersRepository = new CharacterRepository();
            $challenge = $dailyChallenges->ensureCurrent();
            $previousChallenge = $dailyChallenges->findPrevious();
            $characters = $charactersRepository->all();
            $dailySolveCount = $challenge !== null ? $dailyChallenges->countSolves($challenge->id) : 0;
            $yesterdayCharacterName = $previousChallenge !== null
                ? $charactersRepository->findById($previousChallenge->characterId)?->name
                : null;
        } catch (Throwable $exception) {
            $error = 'Database connection failed. Update your DB settings before playing.';
        }

        View::render('game/index', [
            'title' => 'Metal Gear Dle',
            'challenge' => $challenge,
            'characters' => $characters,
            'dailySolveCount' => $dailySolveCount,
            'yesterdayCharacterName' => $yesterdayCharacterName,
            'error' => $error,
            'baseUrl' => $baseUrl,
        ]);
    }

    public function privacy(): void
    {
        View::render('pages/privacy', [
            'title' => 'Privacy Policy | Metal Gear Dle',
            'baseUrl' => (string) (Config::get('app', 'base_url') ?? ''),
        ]);
    }

    public function guess(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $dailyChallenges = new DailyChallengeRepository();
            $characters = new CharacterRepository();
            $gameService = new GameService();
            $challenge = $dailyChallenges->ensureCurrent();
        } catch (Throwable $exception) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Database connection failed. Check your MAMP MySQL credentials.',
            ]);
            return;
        }

        if ($challenge === null) {
            http_response_code(404);
            echo json_encode([
                'error' => 'No daily challenge is available yet. Make sure the characters table has data.',
            ]);
            return;
        }

        $rawGuess = trim((string) ($_POST['guess'] ?? ''));
        $solverToken = trim((string) ($_POST['solver_token'] ?? ''));
        $guess = $characters->findByGuess($rawGuess);
        $solution = $characters->findById($challenge->characterId);

        if ($rawGuess === '' || $guess === null || $solution === null) {
            http_response_code(422);
            echo json_encode([
                'error' => 'Character not found. Try a valid Metal Gear character name or alias.',
            ]);
            return;
        }

        $isWin = $guess->id === $solution->id;
        $dailySolveCount = $dailyChallenges->countSolves($challenge->id);

        if ($isWin && $solverToken !== '') {
            $dailySolveCount = $dailyChallenges->registerSolve($challenge->id, $solverToken);
        }

        echo json_encode([
            'challenge_date' => $challenge->date,
            'guess' => $guess->toArray(),
            'comparison' => $gameService->compare($guess, $solution),
            'is_win' => $isWin,
            'daily_solves_count' => $dailySolveCount,
        ]);
    }
}
