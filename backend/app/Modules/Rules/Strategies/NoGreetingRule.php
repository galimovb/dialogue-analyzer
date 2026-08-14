<?php

namespace App\Modules\Rules\Strategies;

use App\Modules\Dialogues\Models\Dialogue;
use App\Modules\Rules\Contracts\AnalysisRule;
use App\Modules\Rules\DTO\RuleEventDTO;
use App\Modules\Rules\Enums\Severity;
use Illuminate\Support\Str;

/**
 * Первое сообщение менеджера не содержит приветствия.
 */
class NoGreetingRule implements AnalysisRule
{
    public function code(): string
    {
        return 'no_greeting';
    }

    public function name(): string
    {
        return 'Менеджер не поздоровался';
    }

    public function defaultSeverity(): Severity
    {
        return Severity::Low;
    }

    public function defaultConfig(): array
    {
        return [
            'greetings' => ['здравствуй', 'добрый день', 'добрый вечер', 'доброе утро', 'привет'],
        ];
    }

    public function analyze(Dialogue $dialogue, array $config): array
    {
        /** @var array<int, string> $greetings */
        $greetings = $config['greetings'] ?? [];

        $firstManagerMessage = $dialogue->messages
            ->first(fn ($m) => $m->sender_id === $dialogue->manager_id);

        if ($firstManagerMessage === null) {
            return [];
        }

        $text = Str::lower($firstManagerMessage->text);

        foreach ($greetings as $greeting) {
            if (Str::contains($text, Str::lower($greeting))) {
                return [];
            }
        }

        return [
            new RuleEventDTO(
                description: 'Менеджер не поздоровался в первом сообщении.',
                context: ['message_ids' => [$firstManagerMessage->id]],
            ),
        ];
    }
}
