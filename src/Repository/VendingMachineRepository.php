<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\VendingMachineStatus;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class VendingMachineRepository
{
    protected const string VENDING_MACHINE_JSON_FILEPATH = '/config/data/vending_machine_default.json';

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        protected string $projectDir
    ) {
    }

    /**
     * Read data from file.
     *
     * @return VendingMachineStatus
     */
    public function read(): VendingMachineStatus
    {
        return VendingMachineStatus::fromJson($this->getFileContent());
    }

    public function persist(VendingMachineStatus $data): void
    {

    }

    /**
     * Get file content as string.
     *
     * @return string
     */
    protected function getFileContent(): string
    {
        $machineStatusFile = \sprintf('%s%s', $this->projectDir, self::VENDING_MACHINE_JSON_FILEPATH);

        return \file_get_contents($machineStatusFile);
    }
}
