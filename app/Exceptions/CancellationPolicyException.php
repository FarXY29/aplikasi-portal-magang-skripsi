<?php

namespace App\Exceptions;

use RuntimeException;

class CancellationPolicyException extends RuntimeException
{
    public function __construct(
        public readonly ?int $applicationId = null,
        string $message = 'Pembatalan lamaran tidak diizinkan berdasarkan kebijakan yang berlaku.'
    ) {
        parent::__construct($message);
    }
}
