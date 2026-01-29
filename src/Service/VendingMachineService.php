<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\VendingMachineStatus;
use App\Repository\VendingMachineRepository;

class VendingMachineService
{
    public function __construct(
        protected VendingMachineRepository $vendingMachineRepository,
    ) {
    }

    /**
     * Manages the inserted coin.
     *  if the coin is not valid, the balance & the coin inventory is not updated.
     *  When the coin is set to the balance / inventory, persist and return success.
     *
     * @throws \JsonException
     */
    public function insertCoin(string $coin): string
    {
        $machineData = $this->vendingMachineRepository->read();
        $coinValue = \json_decode($coin, true, 512, JSON_THROW_ON_ERROR);

        if (!$this->isValidCoin($coinValue['coin'], $machineData)) {
            return \json_encode($this->returnMessage('error', 'The inserted coin is not valid'));
        }

        $this->manageCoins($coinValue['coin'], $machineData);
        $this->vendingMachineRepository->persist($machineData);

        return \json_encode($this->returnMessage('success', \sprintf('Your balance is %s coins', $machineData->getBalance())));
    }

    public function returnCoins(): void
    {
        // @Todo
    }

    /**
     * Check if the inserted coin is valid.
     *
     * @param float $coin
     * @param VendingMachineStatus $machine
     * @return bool
     */
    protected function isValidCoin(float $coin, VendingMachineStatus $machine): bool
    {
        return \in_array($coin, \array_keys($machine->getCoins()), false);
    }

    /**
     * Manage coins at balance and coin inventory.
     *
     * @param float $coin
     * @param VendingMachineStatus $machine
     * @return void
     */
    protected function manageCoins(float $coin, VendingMachineStatus $machine): void
    {
        $this->updateCoins($coin, $machine);
        $this->updateBalance($coin, $machine);
    }

    /**
     * Update balance.
     *
     * @param float $coin
     * @param VendingMachineStatus $machine
     * @return void
     */
    protected function updateBalance(float $coin, VendingMachineStatus $machine): void
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
    protected function updateCoins(float $coin, VendingMachineStatus $machine): void
    {
        $key = \number_format($coin, 2, '.', '');

        $coins = $machine->getCoins();
        $coins[$key]++;

        $machine->setCoins($coins);
    }

    /**
     * Method to reuse message return.
     * @Todo: Upgrade method.
     *
     * @param string $type
     * @param string $message
     * @return string[]
     */
    protected function returnMessage(string $type, string $message): array
    {
        return [
            'status' => $type,
            'message' => $message,
        ];
    }
}
