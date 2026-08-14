<?php

namespace App\Modules\Analysis\Listeners;

use App\Modules\Analysis\Jobs\AnalyzeDialogueJob;
use App\Modules\Dialogues\Models\Dialogue;
use App\Modules\Rules\Events\RuleUpdated;

/**
 * Изменение любого правила делает прошлые результаты неактуальными —
 * ставим переанализ всех диалогов в очередь. Так пересчёт живёт в Analysis,
 * а Rules лишь публикует факт изменения (Analysis → Rules, не наоборот).
 */
class ReanalyzeDialoguesOnRuleUpdated
{
    public function handle(RuleUpdated $event): void
    {
        Dialogue::query()->pluck('id')
            ->each(fn (int $dialogueId) => AnalyzeDialogueJob::dispatch($dialogueId));
    }
}
