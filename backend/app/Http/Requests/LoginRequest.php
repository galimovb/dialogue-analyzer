<?php

namespace App\Http\Requests;

use App\DTO\LoginData;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ];
    }

    public function toDto(): LoginData
    {
        return new LoginData(
            email: $this->validated('email'),
            password: $this->validated('password'),
            remember: $this->boolean('remember'),
        );
    }
}
