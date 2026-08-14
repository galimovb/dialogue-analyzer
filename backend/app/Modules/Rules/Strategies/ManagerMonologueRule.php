<?php

namespace App\Modules\Rules\Strategies;

use App\Modules\Dialogues\Models\Dialogue;
use App\Modules\Rules\Contracts\AnalysisRule;
use App\Modules\Rules\DTO\RuleEventDTO;
use App\Modules\Rules\Enums\Severity;

/**
 * Менеджер отправил несколько сообщений подряд без ответа клиента.
 */
class ManagerMonologueRule implements AnalysisRule
{
    public function code(): string
    {
        return 'manager_monologue';
    }

    public function name(): string
    {
        return 'Менеджер пишет без ответа';
    }

    public function defaultSeverity(): Severity
    {
        return Severity::Low;
    }

    public function defaultConfig(): array
    {
        return ['max_in_row' => 3];
    }

    public function analyze(Dialogue $dialogue, array $config): array
    {
        $maxInRow = (int) ($config['max_in_row'] ?? 3);
        $events = [];

        /** @var array<int, int> $run — id сообщений текущей серии менеджера */
        $run = [];

        $flush = function () use (&$run, &$events, $maxInRow): void {
            if (count($run) >= $maxInRow) {
                $count = count($run);
                $events[] = new RuleEventDTO(
                    description: "Менеджер отправил {$count} сообщений подряд без ответа клиента.",
                    context: ['count' => $count, 'message_ids' => $run],
                );
            }
            $run = [];
        };

        foreach ($dialogue->messages as $message) {
            if ($message->sender_id === $dialogue->manager_id) {
                $run[] = $message->id;
            } else {
                $flush();
            }
        }

        $flush();

        return $events;
    }
}
