<?php

declare(strict_types=1);

namespace App\DTO;

class Item
{
    public function __construct(
        public readonly string $name,
        public int $qty,
        public readonly float $price,
    ) {
    }

    public function hasStock(): bool
    {
        return $this->qty > 0;
    }

    public function reduceStock(): void
    {
        if ($this->qty > 0) {
            $this->qty--;
        }
    }

    public function setStock(int $qty): void
    {
        if ($qty >= 0) {
            $this->qty = $qty;
        }
    }

    public function toArray(): array
    {
        return [
            'qty' => $this->qty,
            'price' => $this->price,
        ];
    }
}
