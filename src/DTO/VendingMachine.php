<?php

declare(strict_types=1);

namespace App\DTO;

class VendingMachine
{
    public function __construct(
        protected float $balance,
        protected array $coins,
        protected array $items,
    ) {
    }

    /**
     * Create DTO from JSON.
     *
     * @param string $json
     * @return VendingMachine
     */
    public static function fromJson(string $json): VendingMachine
    {
        $data = self::parseJson($json);

        return new VendingMachine(
            balance: $data['balance'],
            coins: self::initCoins($data['coins']),
            items: self::initItems($data['items']),
        );
    }

    /**
     * Get current balance.
     *
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * Get coins available.
     *
     * @return array
     */
    public function getCoins(): array
    {
        return $this->coins;
    }

    /**
     * Get coin if exists.
     *
     * @param string $coin
     * @return Coin|null
     */
    public function getCoin(string $coin): ?Coin
    {
        return $this->coins[$coin] ?? null;
    }

    /**
     * Return amount of coins of a coin.
     *
     * @param float $coin
     * @return int
     */
    public function getCoinAmount(float $coin): int
    {
        /** @var Coin $coinData */
        $coinData = $this->coins[\number_format($coin, 2, '.', '')] ?? 0;

        return $coinData->quantity;
    }

    /**
     * Get items available.
     *
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Return item values.
     *
     * @param string $item
     * @return Item|null
     */
    public function getItem(string $item): ?Item
    {
        return $this->items[$item] ?? null;
    }

    /**
     * Update balance with the inserted coins.
     *
     * @param float $balance
     * @return void
     */
    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function reduceItemStock(string $item): void
    {
        $itemDto = $this->getItem($item);
        if ($itemDto !== null) {
            $itemDto->reduceStock();
        }
    }

    /**
     * Check if coin exists.
     *
     * @param string $coinType
     * @return bool
     */
    public function coinExists(string $coinType): bool
    {
        return isset($this->coins[$coinType]);
    }

    /**
     * Transform DTO into an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'balance' => $this->balance,
            'coins' => $this->coins,
            'items' => $this->items,
        ];
    }

    /**
     * Transform DTO into a JSON string.
     *
     * @return string
     */
    public function toJson(): string
    {
        return \json_encode($this->toArray(), JSON_PRETTY_PRINT);
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
            $coins[$key] = new Coin($key, $coin['quantity']);
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
