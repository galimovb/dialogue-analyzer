<?php

namespace App\Modules\Analysis\Rules;

use App\Modules\Analysis\Contracts\AnalysisRule;
use App\Modules\Analysis\DTO\RuleEventDTO;
use App\Modules\Analysis\Enums\Severity;
use App\Modules\Dialogues\Models\Dialogue;

/**
 * Клиент перестал отвечать: последнее сообщение диалога — от менеджера.
 */
class ClientGhostingRule implements AnalysisRule
{
    public function code(): string
    {
        return 'client_ghosting';
    }

    public function name(): string
    {
        return 'Клиент перестал отвечать';
    }

    public function defaultSeverity(): Severity
    {
        return Severity::High;
    }

    public function defaultConfig(): array
    {
        return ['min_messages' => 2];
    }

    public function analyze(Dialogue $dialogue, array $config): array
    {
        $minMessages = (int) ($config['min_messages'] ?? 2);
        $messages = $dialogue->messages;

        if ($messages->count() < $minMessages) {
            return [];
        }

        $last = $messages->last();
        $clientReplied = $messages->contains(fn ($m) => $m->sender_id === $dialogue->client_id);

        if ($clientReplied && $last->sender_id === $dialogue->manager_id) {
            $number = $messages->count();

            return [
                new RuleEventDTO(
                    description: "Клиент перестал отвечать после сообщения менеджера №{$number}.",
                    context: [
                        'position' => $number,
                        'message_ids' => [$last->id],
                    ],
                ),
            ];
        }

        return [];
    }
}
