<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Config;
use App\Core\Database;
use App\Entities\DailyChallenge;
use DateTimeImmutable;
use PDO;
use PDOException;

final class DailyChallengeRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connection();
    }

    public function ensureCurrent(string $mode = 'classic'): ?DailyChallenge
    {
        $challengeDate = $this->currentChallengeDate();
        $existing = $this->findByDate($challengeDate, $mode);

        if ($existing !== null) {
            return $existing;
        }

        $characterId = $this->pickCharacterId($challengeDate, $mode);

        if ($characterId === null) {
            return null;
        }

        try {
            $statement = $this->pdo->prepare(
                'INSERT INTO daily_challenges (date, character_id, mode) VALUES (:date, :character_id, :mode)'
            );
            $statement->execute([
                'date' => $challengeDate,
                'character_id' => $characterId,
                'mode' => $mode,
            ]);
        } catch (PDOException) {
            // Another request may have created the row first.
        }

        return $this->findByDate($challengeDate, $mode);
    }

    public function findToday(string $mode = 'classic'): ?DailyChallenge
    {
        return $this->findByDate($this->currentChallengeDate(), $mode);
    }

    public function countSolves(int $challengeId): int
    {
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*) FROM daily_challenge_solves WHERE challenge_id = :challenge_id'
        );
        $statement->execute(['challenge_id' => $challengeId]);

        return (int) $statement->fetchColumn();
    }

    public function registerSolve(int $challengeId, string $solverToken): int
    {
        $statement = $this->pdo->prepare(
            'INSERT IGNORE INTO daily_challenge_solves (challenge_id, solver_token) VALUES (:challenge_id, :solver_token)'
        );
        $statement->execute([
            'challenge_id' => $challengeId,
            'solver_token' => $solverToken,
        ]);

        return $this->countSolves($challengeId);
    }

    private function findByDate(string $date, string $mode): ?DailyChallenge
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM daily_challenges WHERE date = :date AND mode = :mode LIMIT 1'
        );
        $statement->execute([
            'date' => $date,
            'mode' => $mode,
        ]);
        $row = $statement->fetch();

        if (!$row) {
            return null;
        }

        return new DailyChallenge(
            (int) $row['id'],
            (string) $row['date'],
            (int) $row['character_id'],
            (string) $row['mode']
        );
    }

    private function currentChallengeDate(): string
    {
        $resetHour = (int) (Config::get('app', 'daily_reset_hour') ?? 16);
        $now = new DateTimeImmutable('now');

        if ((int) $now->format('G') < $resetHour) {
            $now = $now->modify('-1 day');
        }

        return $now->format('Y-m-d');
    }

    private function pickCharacterId(string $challengeDate, string $mode, int $lookbackDays = 4): ?int
    {
        $recentStatement = $this->pdo->prepare(
            'SELECT character_id
             FROM daily_challenges
             WHERE mode = :mode AND date < :date
             ORDER BY date DESC
             LIMIT :limit'
        );
        $recentStatement->bindValue(':mode', $mode);
        $recentStatement->bindValue(':date', $challengeDate);
        $recentStatement->bindValue(':limit', $lookbackDays, PDO::PARAM_INT);
        $recentStatement->execute();
        $recentIds = array_map('intval', $recentStatement->fetchAll(PDO::FETCH_COLUMN));

        if ($recentIds !== []) {
            $placeholders = implode(', ', array_fill(0, count($recentIds), '?'));
            $statement = $this->pdo->prepare(
                sprintf('SELECT id FROM characters WHERE id NOT IN (%s) ORDER BY RAND() LIMIT 1', $placeholders)
            );
            $statement->execute($recentIds);
            $id = $statement->fetchColumn();

            if ($id !== false) {
                return (int) $id;
            }
        }

        $fallback = $this->pdo->query('SELECT id FROM characters ORDER BY RAND() LIMIT 1');
        $id = $fallback->fetchColumn();

        return $id !== false ? (int) $id : null;
    }
}
