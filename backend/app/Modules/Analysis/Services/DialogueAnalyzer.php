<?php

namespace App\Modules\Analysis\Services;

use App\Modules\Analysis\Models\AnalysisEvent;
use App\Modules\Dialogues\Models\Dialogue;
use App\Modules\Rules\Models\Rule;
use App\Modules\Rules\Services\RuleRegistry;

class DialogueAnalyzer
{
    public function __construct(private readonly RuleRegistry $registry) {}

    /**
     * Прогнать все включённые правила по диалогу и сохранить события.
     * Повторный анализ перезаписывает старые события диалога.
     */
    public function analyze(Dialogue $dialogue): void
    {
        $dialogue->loadMissing('messages');

        AnalysisEvent::query()->where('dialogue_id', $dialogue->id)->delete();

        $rules = Rule::query()->where('is_enabled', true)->get();

        foreach ($rules as $rule) {
            $strategy = $this->registry->get($rule->code);

            if ($strategy === null) {
                continue;
            }

            foreach ($strategy->analyze($dialogue, $rule->config ?? []) as $event) {
                AnalysisEvent::create([
                    'dialogue_id' => $dialogue->id,
                    'rule_code' => $rule->code,
                    'severity' => $rule->severity,
                    'description' => $event->description,
                    'context' => $event->context,
                ]);
            }
        }
    }
}
