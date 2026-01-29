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
            coins: self::initCoins($data['coins']),
            items: self::initItems($data['items']),
        );
    }

    /**
     * Get coins.
     *
     * @return array
     */
    public function getCoins(): array
    {
        return $this->coins;
    }

    /**
     * Get items.
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

    /**
     * initialize coins DTOs.
     *
     * @param array $data
     * @return array
     */
    protected static function initCoins(array $data): array
    {
        $coins = [];
        foreach ($data as $key => $coin) {
            $coins[$key] = new Coin($key, $coin);
        }

        return $coins;
    }

    /**
     * Initialize items DTOs.
     *
     * @param array $data
     * @return array
     */
    protected static function initItems(array $data): array
    {
        $items = [];
        foreach ($data as $key => $item) {
            $items[$key] = new Item($key, $item['qty'], $item['price']);
        }

        return $items;
    }
}
