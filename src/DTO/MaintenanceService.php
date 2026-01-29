<?php

declare(strict_types=1);

namespace App\DTO;

class MaintenanceService
{
    public function __construct(
        protected array $coins,
        protected array $items,
    ) {
    }

    public static function fromJson(string $json): self
    {
        $data = self::parseJson($json);

        return new self(
            coins: $data['coins'],
            items: $data['items'],
        );
    }

    /**
     * Get items.
     *
     * @return array
     */
    public function getCoins(): array
    {
        return $this->coins;
    }

    /**
     * Get coins.
     *
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Decode JSON into an array.
     *
     * @param string $json
     * @return array
     */
    protected static function parseJson(string $json): array
    {
        return \json_decode($json, true);
    }
}
