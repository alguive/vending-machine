<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\VendingMachineRepository;
use App\Service\VendingMachineService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class VendingMachineController
{
    public function __construct(
        protected VendingMachineService $vendingMachineService,
    ) {
    }

    /**
     * Get machine status.
     *
     * @throws \Exception
     */
    #[Route('/api/vending-machine', name: 'api_vending_machine', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return JsonResponse::fromJsonString($this->vendingMachineService->getStatus()->toString());
    }

    /**
     * Insert ot return coins.
     *
     * @throws \Exception
     */
    #[Route('/api/vending-machine/coins', name: 'api_vending_machine_insert_coins', methods: ['POST', 'DELETE'])]
    public function coins(Request $request): JsonResponse
    {
        if ($request->getMethod() === 'POST') {
            return JsonResponse::fromJsonString(
                $this->vendingMachineService->insertCoin($request->getContent())->toString()
            );
        }

        return JsonResponse::fromJsonString(
            $this->vendingMachineService->returnCoins()->toString()
        );
    }

    /**
     * Purchase items from the Vending Machine.
     *
     * @throws \Exception
     */
    #[Route('/api/vending-machine/purchase', name: 'api_vending_machine_purchase', methods: ['POST'])]
    public function purchase(Request $request): JsonResponse
    {
        return JsonResponse::fromJsonString($this->vendingMachineService->purchase($request->getContent())->toString());
    }

    /**
     * Make service maintaining to the Vending Machine.
     *
     * @throws \Exception
     */
    #[Route('/api/vending-machine/service', name: 'api_vending_machine_service', methods: ['PUT'])]
    public function service(Request $request): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $this->vendingMachineService->maintenanceService($request->getContent())->toString()
        );
    }
}
