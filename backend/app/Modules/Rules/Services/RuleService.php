<?php

namespace App\Modules\Rules\Services;

use App\Modules\Rules\DTO\RuleDTO;
use App\Modules\Rules\Events\RuleUpdated;
use App\Modules\Rules\Models\Rule;
use Illuminate\Database\Eloquent\Collection;

class RuleService
{
    /**
     * @return Collection<int, Rule>
     */
    public function list(): Collection
    {
        return Rule::query()->orderBy('id')->get();
    }

    /**
     * Обновить настройки правила и уведомить систему об изменении.
     * Пересчёт анализа выполнит слушатель в модуле Analysis —
     * так Rules не знает про Analysis (граф зависимостей ацикличен).
     */
    public function update(int $id, RuleDTO $data): Rule
    {
        $rule = Rule::query()->findOrFail($id);

        $rule->update([
            'is_enabled' => $data->isEnabled,
            'severity' => $data->severity,
            'config' => $data->config,
        ]);

        RuleUpdated::dispatch($rule);

        return $rule;
    }
}
