<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Enum\AvailableMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class VendingMachineController
{
    public function __construct(
    ) {
    }

    /**
     * @throws \Exception
     */
    #[Route('/api/vending-machine', name: 'api_vending_machine')]
    public function index(Request $request): JsonResponse
    {
        if ($request->getMethod() !== AvailableMethod::GET->value) {
            throw new \Exception(\sprintf('Method "%s" is not allowed.', $request->getMethod()));
        }

        return new JsonResponse([]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/api/vending-machine/coins', name: 'api_vending_machine_insert_coins')]
    public function coins(Request $request): JsonResponse
    {
        $requestMethod = $request->getMethod();

        if (
            $requestMethod !== AvailableMethod::POST->value &&
            $requestMethod !== AvailableMethod::DELETE->value
        ) {
            throw new \Exception(\sprintf('Method "%s" is not allowed.', $requestMethod));
        }

        return new JsonResponse([]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/api/vending-machine/purchase', name: 'api_vending_machine_purchase')]
    public function purchase(Request $request): JsonResponse
    {
        if ($request->getMethod() !== AvailableMethod::POST->value) {
            throw new \Exception(\sprintf('Method "%s" is not allowed.', $request->getMethod()));
        }

        return new JsonResponse([]);
    }

    /**
     * @throws \Exception
     */
    public function service(Request $request): JsonResponse
    {
        if ($request->getMethod() !== AvailableMethod::PUT->value) {
            throw new \Exception(\sprintf('Method "%s" is not allowed.', $request->getMethod()));
        }

        return new JsonResponse([]);
    }
}
