<?php

namespace App\Modules\Rules\Strategies;

use App\Modules\Dialogues\Models\Dialogue;
use App\Modules\Rules\Contracts\AnalysisRule;
use App\Modules\Rules\DTO\RuleEventDTO;
use App\Modules\Rules\Enums\Severity;
use Illuminate\Support\Str;

/**
 * В сообщении клиента встречается маркер возражения.
 * Список маркеров хранится в config — расширяется без кода.
 */
class MissedObjectionRule implements AnalysisRule
{
    public function code(): string
    {
        return 'missed_objection';
    }

    public function name(): string
    {
        return 'Возможное возражение клиента';
    }

    public function defaultSeverity(): Severity
    {
        return Severity::Medium;
    }

    public function defaultConfig(): array
    {
        return [
            'markers' => ['дорого', 'дорогова', 'подума', 'конкурент', 'не уверен', 'сомнева', 'не готов'],
        ];
    }

    public function analyze(Dialogue $dialogue, array $config): array
    {
        /** @var array<int, string> $markers */
        $markers = $config['markers'] ?? [];
        $events = [];

        foreach ($dialogue->messages as $message) {
            if ($message->sender_id !== $dialogue->client_id) {
                continue;
            }

            $text = Str::lower($message->text);

            foreach ($markers as $marker) {
                if (Str::contains($text, Str::lower($marker))) {
                    $events[] = new RuleEventDTO(
                        description: "Обнаружено возможное возражение клиента (маркер «{$marker}»).",
                        context: [
                            'marker' => $marker,
                            'message_ids' => [$message->id],
                        ],
                    );

                    break;
                }
            }
        }

        return $events;
    }
}
