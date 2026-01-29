<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\VendingMachineStatus;

class CoinService
{
    protected const array AVAILABLE_CHANGE_COINS = [0.05, 0.10, 0.25];

    /**
     * Check if the inserted coin is valid.
     *
     * @param float $coin
     * @param VendingMachineStatus $machine
     * @return bool
     */
    public function isValidCoin(float $coin, VendingMachineStatus $machine): bool
    {
        return \in_array($coin, \array_keys($machine->getCoins()), false);
    }

    /**
     * Check if machine has enough change for the user.
     *
     * @param VendingMachineStatus $machine
     * @return bool
     */
    public function hasEnoughChange(VendingMachineStatus $machine): bool
    {
        $totalBalance = $machine->getBalance();
        $changeCoins = $this->sortAvailableChangeCoins();

        foreach ($changeCoins as $change) {
            $totalStock = $machine->getCoinAmount($change);
            $totalBalance = \max($totalBalance - ($change * $totalStock), 0);
        }

        return (int) $totalBalance === 0;
    }

    public function resetBalance(VendingMachineStatus $machine): void
    {

    }

    /**
     * Update balance.
     *
     * @param float $coin
     * @param VendingMachineStatus $machine
     * @return void
     */
    public function updateBalance(float $coin, VendingMachineStatus $machine): void
    {
        $currentBalance = $machine->getBalance();
        $machine->setBalance(\round($currentBalance + $coin, 2));
    }

    /**
     * Update coins.
     *
     * @param float $coin
     * @param VendingMachineStatus $machine
     * @return void
     */
    public function updateCoins(float $coin, VendingMachineStatus $machine): void
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
        rsort($sorted);

        return $sorted;
    }
}
