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
            coins: $data['coins'],
            items: $data['items'],
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
     * Return amount of coins of coin.
     *
     * @param float $coin
     * @return int
     */
    public function getCoinAmount(float $coin): int
    {
        return $this->coins[\number_format($coin, 2, '.', '')] ?? 0;
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
     * @return array
     */
    public function getItem(string $item): array
    {
        return $this->items[$item] ?? [];
    }

    /**
     * Set amount for specific coin.
     *
     * @param float $coin
     * @param int $amount
     * @return void
     */
    public function setCoinAmount(float $coin, int $amount): void
    {
        $this->coins[\number_format($coin, 2, '.', '')] = $amount;
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

    /**
     * Update Coins.
     *
     * @param array $coins
     * @return void
     */
    public function setCoins(array $coins): void
    {
        $this->coins = $coins;
    }

    /**
     * Update Items.
     *
     * @param array $items
     * @return void
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * Set item values.
     *
     * @param string $item
     * @param array $values
     * @return void
     */
    public function setItemData(string $item, array $values): void
    {
        $this->items[$item] = $values;
    }

    public function reduceItemStock(string $item): void
    {
        $itemData = $this->getItem($item);
        $itemData['qty'] -= 1;

        $this->setItemData($item, $itemData);
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
}
