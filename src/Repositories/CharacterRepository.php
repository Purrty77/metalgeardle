<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Entities\Character;
use PDO;

final class CharacterRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connection();
    }

    /**
     * @return Character[]
     */
    public function all(): array
    {
        $statement = $this->pdo->query('SELECT * FROM characters ORDER BY name ASC');

        return array_map([$this, 'mapCharacter'], $statement->fetchAll());
    }

    public function findById(int $id): ?Character
    {
        $statement = $this->pdo->prepare('SELECT * FROM characters WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row ? $this->mapCharacter($row) : null;
    }

    public function findByGuess(string $guess): ?Character
    {
        $statement = $this->pdo->prepare(
            'SELECT c.*
             FROM characters c
             LEFT JOIN character_aliases a ON a.character_id = c.id
             WHERE LOWER(c.name) = LOWER(:guess)
                OR LOWER(a.alias_name) = LOWER(:guess)
             LIMIT 1'
        );
        $statement->execute(['guess' => trim($guess)]);
        $row = $statement->fetch();

        return $row ? $this->mapCharacter($row) : null;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function mapCharacter(array $row): Character
    {
        return new Character(
            (int) $row['id'],
            (string) $row['name'],
            (string) $row['gender'],
            (string) $row['affiliation'],
            (string) $row['nationality'],
            (string) $row['first_game'],
            (string) $row['era'],
            (string) $row['role_type'],
            $row['image_small'] !== null ? (string) $row['image_small'] : null,
            $row['image_large'] !== null ? (string) $row['image_large'] : null
        );
    }
}
