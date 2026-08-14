<?php

namespace App\Modules\Rules\Strategies;

use App\Modules\Dialogues\Models\Dialogue;
use App\Modules\Rules\Contracts\AnalysisRule;
use App\Modules\Rules\DTO\RuleEventDTO;
use App\Modules\Rules\Enums\Severity;

/**
 * Клиент написал, а менеджер ответил позже порога.
 */
class SlowResponseRule implements AnalysisRule
{
    public function code(): string
    {
        return 'slow_response';
    }

    public function name(): string
    {
        return 'Долгий ответ менеджера';
    }

    public function defaultSeverity(): Severity
    {
        return Severity::Medium;
    }

    public function defaultConfig(): array
    {
        return ['threshold_minutes' => 30];
    }

    public function analyze(Dialogue $dialogue, array $config): array
    {
        $threshold = (int) ($config['threshold_minutes'] ?? 30);
        $events = [];
        $previous = null;

        foreach ($dialogue->messages as $message) {
            if ($previous !== null
                && $previous->sender_id === $dialogue->client_id
                && $message->sender_id === $dialogue->manager_id
            ) {
                $minutes = (int) $previous->sent_at->diffInMinutes($message->sent_at);

                if ($minutes > $threshold) {
                    $events[] = new RuleEventDTO(
                        description: "Менеджер ответил через {$minutes} мин (порог — {$threshold} мин).",
                        context: [
                            'minutes' => $minutes,
                            'message_ids' => [$previous->id, $message->id],
                        ],
                    );
                }
            }

            $previous = $message;
        }

        return $events;
    }
}
