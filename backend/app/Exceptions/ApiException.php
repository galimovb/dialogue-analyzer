<?php

namespace App\Exceptions;

use App\Enums\ErrorCode;
use Exception;

class ApiException extends Exception
{
    public readonly ErrorCode $error;

    public readonly int $status;

    public function __construct(ErrorCode $error, ?string $errorMessage = null, ?int $status = null)
    {
        $this->error = $error;
        $this->status = $status ?? $error->status();

        parent::__construct($errorMessage ?? $error->message());
    }
}
