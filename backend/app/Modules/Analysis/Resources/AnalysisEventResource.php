<?php

namespace App\Modules\Analysis\Resources;

use App\Modules\Analysis\Models\AnalysisEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AnalysisEvent
 */
class AnalysisEventResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rule_code' => $this->rule_code,
            'severity' => $this->severity,
            'description' => $this->description,
            'context' => $this->context,
            'created_at' => $this->created_at,
        ];
    }
}
