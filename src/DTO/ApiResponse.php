<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * DTO to easy manage responses.
 */
class ApiResponse
{
    protected const string ERROR = 'error';
    protected const string SUCCESS = 'success';

    public function __construct(
        public readonly string $status,
        public readonly string $message,
        public readonly array $data,
    ) {
    }

    public static function success(string $message, array $data = []): self
    {
        return new self(
            status: self::SUCCESS,
            message: $message,
            data: $data,
        );
    }

    public static function error(string $message, array $data = []): self
    {
        return new self(
            status: self::ERROR,
            message: $message,
            data: $data,
        );
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    /**
     * @throws \JsonException
     */
    public function toString(): string
    {
        return \json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }
}
