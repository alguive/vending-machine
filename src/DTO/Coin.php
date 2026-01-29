<?php

declare(strict_types=1);

namespace App\DTO;

class Coin
{
    public function __construct(
        public readonly string $value,
        public int $quantity,
    ) {
    }

    public function increase(): void
    {
        $this->quantity++;
    }

    public function decrease(): void
    {
        if ($this->quantity > 0) {
            $this->quantity--;
        }
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = \max($quantity, 0);
    }

    public function toArray(): array
    {
        return [
            $this->value => $this->quantity,
        ];
    }
}
