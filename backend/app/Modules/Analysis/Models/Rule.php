<?php

namespace App\Modules\Analysis\Models;

use App\Modules\Analysis\Enums\Severity;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Настройки правила анализа (логика — в классе App\Modules\Analysis\Rules\*, связь по code).
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property Severity $severity
 * @property bool $is_enabled
 * @property array<string, mixed>|null $config
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['code', 'name', 'severity', 'is_enabled', 'config'])]
class Rule extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'severity' => Severity::class,
            'is_enabled' => 'boolean',
            'config' => 'array',
        ];
    }
}
