<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\VendingMachineStatus;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class VendingMachineRepository
{
    protected const string RUNTIME_JSON_PATH = '/var/data/vending_machine.json';
    protected const string DEFAULT_JSON_PATH = '/config/data/vending_machine_default.json';

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

    /**
     * Get file content as string.
     *
     * @return string
     */
    protected function getFileContent(): string
    {
        $runtimePath = \sprintf('%s%s', $this->projectDir, self::RUNTIME_JSON_PATH);
        $defaultPath = \sprintf('%s%s', $this->projectDir, self::DEFAULT_JSON_PATH);

        $path = file_exists($runtimePath) ? $runtimePath : $defaultPath;

        return \file_get_contents($path);
    }

    /**
     * Persist data on runtime file.
     *  Create file if not exists.
     *
     * @param VendingMachineStatus $data
     * @return void
     */
    public function persist(VendingMachineStatus $data): void
    {
        $machineStatusFile = \sprintf('%s%s', $this->projectDir, self::RUNTIME_JSON_PATH);
        $dir = \dirname($machineStatusFile);

        if (!\is_dir($dir)) {
            \mkdir($dir, 0755, true);
        }

        \file_put_contents($machineStatusFile, $data->toJson());
    }
}
