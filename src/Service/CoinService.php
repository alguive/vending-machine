<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Coin;
use App\DTO\VendingMachine;

class CoinService
{
    protected const array AVAILABLE_CHANGE_COINS = [0.05, 0.10, 0.25];

    /**
     * Check if the inserted coin is valid.
     *
     * @param string $insertedCoin
     * @param VendingMachine $machine
     * @return bool
     */
    public function isValidCoin(string $insertedCoin, VendingMachine $machine): bool
    {
        return \in_array($insertedCoin, \array_keys($machine->getCoins()), true);
    }

    /**
     * Check if machine has enough change for the user.
     *
     * @param VendingMachine $machine
     * @return bool
     */
    public function hasEnoughChange(VendingMachine $machine): bool
    {
        $totalBalance = $machine->getBalance();
        $changeCoins = $this->sortAvailableChangeCoins();

        foreach ($changeCoins as $change) {
            $changeStr = \number_format($change, 2, '.', '');
            $totalStock = $machine->getCoin($changeStr)?->quantity ?? 0;
            $totalBalance = \max($totalBalance - ($change * $totalStock), 0);
        }

        return (int) $totalBalance === 0;
    }

    /**
     * Set balance.
     *
     * @param float $amount
     * @param VendingMachine $machine
     * @return void
     */
    public function decreaseBalance(float $amount, VendingMachine $machine): void
    {
        $newBalance = \round($machine->getBalance() - $amount, 2);
        $machine->setBalance($newBalance);
    }

    /**
     * Calculate change.
     *
     * @param float $amount
     * @param VendingMachine $machine
     * @return array
     *
     * @throws \Exception
     */
    public function calculateChange(VendingMachine $machine, float $amount = 0.0): array
    {
        $coinsToReturn = [];
        $totalBalance = round($machine->getBalance() - $amount, 2);

        foreach ($this->sortAvailableChangeCoins() as $coin) {
            $coinStr = number_format($coin, 2, '.', '');
            $coinDto = $machine->getCoin($coinStr);
            $available = $coinDto->quantity ?? 0;

            while ($totalBalance > 0 && $available > 0 && $totalBalance >= $coin) {
                $coinsToReturn[] = $coin;
                $totalBalance = round($totalBalance - $coin, 2);
                $coinDto->decrease();
            }
        }

        if ($totalBalance > 0) {
            throw new \Exception('Not enough change.');
        }

        return $coinsToReturn;
    }

    /**
     * To call every time the machine returns coins or buy something and need to return coins.
     *
     * @param array $coinsToReturn
     * @param VendingMachine $machine
     * @return void
     */
    public function decreaseCoins(array $coinsToReturn, VendingMachine $machine): void
    {
        foreach ($coinsToReturn as $coin) {
            $coinStr = \number_format($coin, 2, '.', '');
            $coinDto = $machine->getCoin($coinStr);
            $coinDto->decrease();
        }
    }

    /**
     * Update balance.
     *
     * @param string $insertedCoin
     * @param VendingMachine $machine
     * @return void
     */
    public function updateBalance(string $insertedCoin, VendingMachine $machine): void
    {
        $currentBalance = $machine->getBalance();
        $machine->setBalance(\round($currentBalance + (float) $insertedCoin, 2));
    }

    /**
     * Update change coins on the machine.
     *
     * @param array $coins
     * @param VendingMachine $machine
     * @return void
     */
    public function setCoinChange(array $coins, VendingMachine $machine): void
    {
        foreach ($coins as $coin => $data) {
            if ($machine->coinExists($coin)) {
                $machine->getCoin($coin)->setQuantity($data->quantity);
            }
        }
    }

    /**
     * Update coins.
     *
     * @param string $insertedCoin
     * @param VendingMachine $machine
     * @return void
     */
    public function incrementCoin(string $insertedCoin, VendingMachine $machine): void
    {
        $machineCoin = $machine->getCoin($insertedCoin);
        $machineCoin?->increase();
    }

    /**
     * Sort array values DESC.
     *
     * @return array
     */
    protected function sortAvailableChangeCoins(): array
    {
        $sorted = self::AVAILABLE_CHANGE_COINS;
        \rsort($sorted);

        return $sorted;
    }
}
