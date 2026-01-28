<?php

declare(strict_types=1);

namespace App\Enum;

enum AvailableMethod: string
{
    case PUT = 'PUT';
    case GET = 'GET';
    case POST = 'POST';
    case DELETE = 'DELETE';

    /**
     * Return the values of all the options.
     *
     * @return array
     */
    public static function values(): array
    {
        return \array_column(self::cases(), 'value');
    }
}
