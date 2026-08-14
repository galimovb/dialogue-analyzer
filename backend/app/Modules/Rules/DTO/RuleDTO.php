<?php

namespace App\Modules\Rules\DTO;

use App\Modules\Rules\Enums\Severity;

class RuleDTO
{
    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(
        public bool $isEnabled,
        public Severity $severity,
        public array $config,
    ) {}
}
