<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\VendingMachineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class VendingMachineController
{
    public function __construct(
        protected VendingMachineRepository $vendingMachineRepository,
    ) {
        // Empty constructor
    }

    /**
     * @throws \Exception
     */
    #[Route('/api/vending-machine', name: 'api_vending_machine', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        return new JsonResponse([]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/api/vending-machine/coins', name: 'api_vending_machine_insert_coins', methods: ['POST', 'DELETE'])]
    public function coins(Request $request): JsonResponse
    {

        return new JsonResponse([]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/api/vending-machine/purchase', name: 'api_vending_machine_purchase', methods: ['POST'])]
    public function purchase(Request $request): JsonResponse
    {
        return new JsonResponse([]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/api/vending-machine/services', name: 'api_vending_machine_services', methods: ['GET'])]
    public function service(Request $request): JsonResponse
    {
        return new JsonResponse([]);
    }
}
