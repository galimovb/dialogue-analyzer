<?php

namespace App\Modules\Rules\Events;

use App\Modules\Rules\Models\Rule;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Настройки правила изменились. Модуль Analysis слушает это событие,
 * чтобы пересчитать анализ — так Rules не зависит от Analysis (граф ацикличен).
 */
class RuleUpdated
{
    use Dispatchable;

    public function __construct(public readonly Rule $rule) {}
}
