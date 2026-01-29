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
        protected StockService $stockService,
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
        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage());
        }

        $this->vendingMachineRepository->persist($machineData);

        return ApiResponse::success(
            \sprintf('Returned %s', $balance),
            $coinsToReturn
        );
    }

    /**
     * @throws \JsonException
     */
    public function purchase(string $item): ApiResponse
    {
        $machineData = $this->vendingMachineRepository->read();
        $balance = \round($machineData->getBalance(), 2);
        $item = \json_decode($item, true, 512, JSON_THROW_ON_ERROR)['item'];

        $itemPrice = $machineData->getItem($item)['price'];

        if ($itemPrice > $balance) {
            return ApiResponse::error('No enough balance to purchase.', $this->buildResponseData($machineData));
        }

        try {
            $this->stockService->validateItem($item, $machineData);
        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), $this->buildResponseData($machineData));
        }

        $this->coinService->decreaseBalance($itemPrice, $machineData);
        $this->stockService->reduceStock($item, $machineData);
        $this->vendingMachineRepository->persist($machineData);

        return ApiResponse::success('Item purchased successfully.', $this->buildResponseData($machineData)
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

    /**
     * Merge arrays to return information to the user.
     *
     * @param $machineData
     * @return array
     */
    protected function buildResponseData($machineData): array
    {
        return \array_merge(['Balance' => $machineData->getBalance()], $machineData->getItems());
    }
}
