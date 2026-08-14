<?php

namespace App\Modules\Rules\Services;

use App\Modules\Rules\Contracts\AnalysisRule;

class RuleRegistry
{
    /**
     * @var array<string, AnalysisRule>|null
     */
    private ?array $rules = null;

    /**
     * Все доступные правила, ключ — code.
     *
     * @return array<string, AnalysisRule>
     */
    public function all(): array
    {
        if ($this->rules !== null) {
            return $this->rules;
        }

        $this->rules = [];

        foreach (glob(app_path('Modules/Rules/Strategies/*.php')) ?: [] as $file) {
            $class = 'App\\Modules\\Rules\\Strategies\\'.basename($file, '.php');

            if (is_subclass_of($class, AnalysisRule::class)) {
                /** @var AnalysisRule $rule */
                $rule = app($class);
                $this->rules[$rule->code()] = $rule;
            }
        }

        return $this->rules;
    }

    public function get(string $code): ?AnalysisRule
    {
        return $this->all()[$code] ?? null;
    }
}
