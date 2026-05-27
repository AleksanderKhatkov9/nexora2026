<?php

namespace App\Integrations;

class IntegrationTestResult
{
    public function __construct(
        public readonly bool $success,
        public readonly string $message,
        public readonly array $details = [],
    ) {}

    public static function success(string $message, array $details = []): self
    {
        return new self(true, $message, $details);
    }

    public static function failed(string $message, array $details = []): self
    {
        return new self(false, $message, $details);
    }
}
