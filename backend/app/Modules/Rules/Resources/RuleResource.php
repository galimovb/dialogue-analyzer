<?php

namespace App\Modules\Rules\Resources;

use App\Modules\Rules\Models\Rule;
use App\Modules\Rules\Services\RuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Rule
 */
class RuleResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'severity' => $this->severity,
            'is_enabled' => $this->is_enabled,
            'config' => $this->config ?? [],
            // Дефолтные ключи из класса-стратегии — подсказка админу, что можно настраивать.
            'default_config' => app(RuleRegistry::class)->get($this->code)?->defaultConfig() ?? [],
            'updated_at' => $this->updated_at,
        ];
    }
}
