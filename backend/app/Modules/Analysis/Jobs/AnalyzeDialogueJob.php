<?php

namespace App\Modules\Analysis\Jobs;

use App\Modules\Analysis\Services\DialogueAnalyzer;
use App\Modules\Dialogues\Models\Dialogue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AnalyzeDialogueJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $dialogueId) {}

    public function handle(DialogueAnalyzer $analyzer): void
    {
        $dialogue = Dialogue::query()->with('messages')->find($this->dialogueId);

        if ($dialogue !== null) {
            $analyzer->analyze($dialogue);
        }
    }
}
