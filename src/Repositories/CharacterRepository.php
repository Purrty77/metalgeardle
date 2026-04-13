<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Config;
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
        $rows = $statement->fetchAll();
        $aliasesById = $this->fetchAliasesByCharacterIds(array_map(
            static fn (array $row): int => (int) $row['id'],
            $rows
        ));

        return array_map(
            fn (array $row): Character => $this->mapCharacter($row, $aliasesById[(int) $row['id']] ?? []),
            $rows
        );
    }

    public function findById(int $id): ?Character
    {
        $statement = $this->pdo->prepare('SELECT * FROM characters WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row ? $this->mapCharacter($row, $this->fetchAliasesByCharacterId((int) $row['id'])) : null;
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

        return $row ? $this->mapCharacter($row, $this->fetchAliasesByCharacterId((int) $row['id'])) : null;
    }

    /**
     * @param array<string, mixed> $row
     * @param string[] $aliases
     */
    private function mapCharacter(array $row, array $aliases = []): Character
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
            isset($row['codec_frequency']) && $row['codec_frequency'] !== null ? (string) $row['codec_frequency'] : null,
            $row['image_small'] !== null ? $this->normalizeAssetPath((string) $row['image_small']) : null,
            $row['image_large'] !== null ? $this->normalizeAssetPath((string) $row['image_large']) : null,
            $aliases
        );
    }

    /**
     * @param int[] $characterIds
     * @return array<int, string[]>
     */
    private function fetchAliasesByCharacterIds(array $characterIds): array
    {
        $characterIds = array_values(array_unique(array_filter($characterIds, static fn (int $id): bool => $id > 0)));

        if ($characterIds === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($characterIds), '?'));
        $statement = $this->pdo->prepare(
            "SELECT character_id, alias_name
             FROM character_aliases
             WHERE character_id IN ($placeholders)
             ORDER BY alias_name ASC"
        );
        $statement->execute($characterIds);

        $aliasesById = [];

        foreach ($statement->fetchAll() as $row) {
            $characterId = (int) $row['character_id'];
            $aliasesById[$characterId] ??= [];
            $aliasesById[$characterId][] = (string) $row['alias_name'];
        }

        return $aliasesById;
    }

    /**
     * @return string[]
     */
    private function fetchAliasesByCharacterId(int $characterId): array
    {
        return $this->fetchAliasesByCharacterIds([$characterId])[$characterId] ?? [];
    }

    private function normalizeAssetPath(string $path): string
    {
        $baseUrl = rtrim((string) (Config::get('app', 'base_url') ?? ''), '/');

        if ($baseUrl === '' || !str_starts_with($path, '/public/')) {
            return $path;
        }

        return $baseUrl . $path;
    }
}
