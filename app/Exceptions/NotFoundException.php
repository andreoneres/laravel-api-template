<?php

declare(strict_types=1);

namespace App\Exceptions;

class NotFoundException extends AppException
{
    public function __construct(string $message, int $code = 404)
    {
        parent::__construct($message, $code);
    }
}
