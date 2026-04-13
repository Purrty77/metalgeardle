<?php

declare(strict_types=1);

namespace App\Entities;

final class Character
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $gender,
        public readonly string $affiliation,
        public readonly string $nationality,
        public readonly string $firstGame,
        public readonly string $era,
        public readonly string $roleType,
        public readonly ?string $codecFrequency = null,
        public readonly ?string $imageSmall = null,
        public readonly ?string $imageLarge = null,
        public readonly array $aliases = []
    ) {
    }

    /**
     * @return array<string, scalar|null>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'gender' => $this->gender,
            'affiliation' => $this->affiliation,
            'nationality' => $this->nationality,
            'first_game' => $this->firstGame,
            'era' => $this->era,
            'role_type' => $this->roleType,
            'codec_frequency' => $this->codecFrequency,
            'image_small' => $this->imageSmall,
            'image_large' => $this->imageLarge,
            'aliases' => $this->aliases,
        ];
    }
}
