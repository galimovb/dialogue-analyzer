<?php

namespace Database\Seeders;

use App\Modules\Rules\Models\Rule;
use App\Modules\Rules\Services\RuleRegistry;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    public function __construct(private readonly RuleRegistry $registry) {}

    public function run(): void
    {
        $rules = $this->registry->all();

        foreach ($rules as $rule) {
            // firstOrCreate: новые правила заводятся с дефолтами,
            // существующие настройки (severity/config/is_enabled) не перезаписываются.
            Rule::firstOrCreate(
                ['code' => $rule->code()],
                [
                    'name' => $rule->name(),
                    'severity' => $rule->defaultSeverity(),
                    'is_enabled' => true,
                    'config' => $rule->defaultConfig(),
                ],
            );
        }

        // Прунинг «сирот»: правила, чей класс удалён из кода.
        // Только если правила реально найдены — иначе whereNotIn([]) снесёт всю таблицу.
        if ($rules !== []) {
            Rule::query()
                ->whereNotIn('code', array_keys($rules))
                ->delete();
        }
    }
}
