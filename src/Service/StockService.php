<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Item;
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
        $itemDto = $machine->getItem($item);

        if (!$itemDto instanceof Item) {
            throw new \Exception('Item not found on the inventory.');
        }

        if (!$itemDto->hasStock()) {
            throw new \Exception(\sprintf('Item %s without stock.', $item));
        }
    }

    /**
     * Call DTO to reduce Stock.
     *
     * @param string $item
     * @param VendingMachine $machine
     * @return void
     */
    public function reduceStock(string $item, VendingMachine $machine): void
    {
        $machine->reduceItemStock($item);
    }

    public function updateItemsData(array $items, VendingMachine $machine): void
    {
        foreach ($items as $key => $data) {
            $itemDto = $machine->getItem($key);

            if ($itemDto instanceof Item) {
                $itemDto->setStock($data->qty);
            }
        }
    }
}
