<?php

namespace App\DTO;

final class LoginData
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
    ) {}
}
