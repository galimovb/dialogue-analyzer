<?php

namespace App\Modules\Auth\DTO;

final class LoginDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
    ) {}
}
