<?php

namespace App\Modules\Analysis\Services;

use App\Modules\Analysis\Models\AnalysisEvent;
use Illuminate\Support\Collection;

class AnalysisEventService
{
    /**
     * События анализа диалога, от самых критичных к наименее.
     *
     * @return Collection<int, AnalysisEvent>
     */
    public function forDialogue(int $id): Collection
    {
        return AnalysisEvent::query()
            ->where('dialogue_id', $id)
            ->get()
            ->sortByDesc(fn (AnalysisEvent $event) => $event->severity->weight())
            ->values();
    }
}
