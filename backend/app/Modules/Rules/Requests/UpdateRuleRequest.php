<?php

namespace App\Modules\Rules\Requests;

use App\Modules\Rules\DTO\RuleDTO;
use App\Modules\Rules\Enums\Severity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'is_enabled' => ['required', 'boolean'],
            'severity' => ['required', new Enum(Severity::class)],
            'config' => ['present', 'array'],
        ];
    }

    public function toDto(): RuleDTO
    {
        return new RuleDTO(
            isEnabled: $this->boolean('is_enabled'),
            severity: Severity::from($this->string('severity')->value()),
            config: $this->input('config', []),
        );
    }
}
