<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\VendingMachine;

class StockService
{
    /**
     * Just to check if the item exists and has stock.
     *
     * @param string $item
     * @param VendingMachine $machine
     * @return void
     * @throws \Exception
     */
    public function validateItem(string $item, VendingMachine $machine): void
    {
        $items = $machine->getItems();

        if (!\array_key_exists($item, $items)) {
            throw new \Exception('Item not found on the inventory.');
        }

        if ($items[$item]['qty'] === 0) {
            throw new \Exception(\sprintf('Item %s without stock.', $item));
        }
    }

    /**
     * Call DTO to reduce Stock.
     *
     * @param string $item
     * @param VendingMachine $vendingMachine
     * @return void
     */
    public function reduceStock(string $item, VendingMachine $vendingMachine): void
    {
        $vendingMachine->reduceItemStock($item);
    }

    public function updateItemsData(array $items, VendingMachine $vendingMachine): void
    {

    }
}
