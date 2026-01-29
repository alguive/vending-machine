<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\VendingMachine;

class CoinService
{
    protected const array AVAILABLE_CHANGE_COINS = [0.05, 0.10, 0.25];

    /**
     * Check if the inserted coin is valid.
     *
     * @param float $coin
     * @param VendingMachine $machine
     * @return bool
     */
    public function isValidCoin(float $coin, VendingMachine $machine): bool
    {
        return \in_array($coin, \array_keys($machine->getCoins()), false);
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
            $totalStock = $machine->getCoinAmount($change);
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
        $totalBalance = \round($machine->getBalance() - $amount, 2);

        foreach ($this->sortAvailableChangeCoins() as $coin) {
            $totalCoins = $machine->getCoinAmount($coin);

            while ($totalBalance > 0 && $totalCoins > 0) {
                $coinsToReturn[] = $coin;
                $totalBalance = \round($totalBalance - $coin, 2);
                $totalCoins--;
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
    public function decrementCoins(array $coinsToReturn, VendingMachine $machine): void
    {
        foreach ($coinsToReturn as $coin) {
            $total = $machine->getCoinAmount($coin);
            $machine->setCoinAmount($coin, --$total);
        }
    }

    /**
     * Update balance.
     *
     * @param float $coin
     * @param VendingMachine $machine
     * @return void
     */
    public function updateBalance(float $coin, VendingMachine $machine): void
    {
        $currentBalance = $machine->getBalance();
        $machine->setBalance(\round($currentBalance + $coin, 2));
    }

    /**
     * Update coins.
     *
     * @param float $coin
     * @param VendingMachine $machine
     * @return void
     */
    public function updateCoins(float $coin, VendingMachine $machine): void
    {
        $key = \number_format($coin, 2, '.', '');

        $coins = $machine->getCoins();
        $coins[$key]++;

        $machine->setCoins($coins);
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
