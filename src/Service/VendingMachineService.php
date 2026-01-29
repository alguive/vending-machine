<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\ApiResponse;
use App\DTO\VendingMachine;
use App\Repository\VendingMachineRepository;

class VendingMachineService
{
    public function __construct(
        protected CoinService $coinService,
        protected VendingMachineRepository $vendingMachineRepository,
    ) {
    }

    /**
     * Manages the inserted coin.
     *  If the coin is not valid, the balance & the coin inventory is not updated.
     *  If the coin inserted has value of 1, then check if the machine has enough change. If not, reject coin.
     *  When the coin is set to the balance / inventory, persist and return success.
     *
     * @throws \JsonException
     */
    public function insertCoin(string $coin): ApiResponse
    {
        $machineData = $this->vendingMachineRepository->read();
        $coinValue = \json_decode($coin, true, 512, JSON_THROW_ON_ERROR);

        if (!$this->coinService->isValidCoin($coinValue['coin'], $machineData)) {
            return ApiResponse::error('The inserted coin is not valid');
        }

        $this->manageCoins($coinValue['coin'], $machineData);

        if ($this->coinService->hasEnoughChange($machineData)) {
            $this->vendingMachineRepository->persist($machineData);
        } else {
            return ApiResponse::error(\sprintf('Rejected. Not enough change for %s.', $machineData->getBalance()));
        }

        return ApiResponse::success(\sprintf('Your balance is %s coins', $machineData->getBalance()));
    }

    /**
     * Return coins when user wants them back.
     *
     * @return ApiResponse
     */
    public function returnCoins(): ApiResponse
    {
        $machineData = $this->vendingMachineRepository->read();
        $balance = \round($machineData->getBalance(), 2);

        if ($balance === 0.0) {
            return ApiResponse::error('No coins to return.');
        }

        try {
            $coinsToReturn = $this->coinService->calculateChange($machineData);
            $this->coinService->decrementCoins($coinsToReturn, $machineData);
            $this->coinService->decreaseBalance($balance, $machineData);
        } catch (\Throwable $exception) {
            return ApiResponse::error($exception->getMessage());
        }

        $this->vendingMachineRepository->persist($machineData);

        return ApiResponse::success(
            \sprintf('Returned %s', $balance),
            $coinsToReturn
        );
    }

    /**
     * Manage coins at balance and coin inventory.
     *
     * @param float $coin
     * @param VendingMachine $machine
     * @return void
     */
    protected function manageCoins(float $coin, VendingMachine $machine): void
    {
        $this->coinService->updateCoins($coin, $machine);
        $this->coinService->updateBalance($coin, $machine);
    }
}
