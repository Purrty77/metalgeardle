<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

final class SuggestionRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connection();
    }

    public function hasSubmittedForDate(string $suggestionToken, string $suggestionDate): bool
    {
        $statement = $this->pdo->prepare(
            'SELECT 1
             FROM suggestions
             WHERE suggestion_token = :suggestion_token
               AND suggestion_date = :suggestion_date
             LIMIT 1'
        );
        $statement->execute([
            'suggestion_token' => $suggestionToken,
            'suggestion_date' => $suggestionDate,
        ]);

        return $statement->fetchColumn() !== false;
    }

    public function create(string $suggestionToken, string $suggestionDate, string $title, string $body): void
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO suggestions (suggestion_token, suggestion_date, title, body)
             VALUES (:suggestion_token, :suggestion_date, :title, :body)'
        );
        $statement->execute([
            'suggestion_token' => $suggestionToken,
            'suggestion_date' => $suggestionDate,
            'title' => $title,
            'body' => $body,
        ]);
    }
}
