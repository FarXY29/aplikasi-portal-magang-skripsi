<?php

namespace App\Exceptions;

use RuntimeException;

class InvalidApplicationStateTransitionException extends RuntimeException
{
    public function __construct(
        public readonly string $fromStatus,
        public readonly string $toStatus,
        public readonly ?int $applicationId = null,
        string $message = ''
    ) {
        $msg = $message ?: "Transisi status lamaran magang tidak valid dari [{$fromStatus}] ke [{$toStatus}]" . ($applicationId ? " untuk lamaran ID #{$applicationId}." : '.');
        parent::__construct($msg);
    }
}
