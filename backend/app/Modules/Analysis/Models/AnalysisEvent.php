<?php

namespace App\Modules\Analysis\Models;

use App\Modules\Dialogues\Models\Dialogue;
use App\Modules\Rules\Enums\Severity;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Найденное событие анализа (результат прогона правила по диалогу).
 *
 * @property int $id
 * @property int $dialogue_id
 * @property string $rule_code
 * @property Severity $severity
 * @property string $description
 * @property array<string, mixed>|null $context
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['dialogue_id', 'rule_code', 'severity', 'description', 'context'])]
class AnalysisEvent extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'severity' => Severity::class,
            'context' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Dialogue, $this>
     */
    public function dialogue(): BelongsTo
    {
        return $this->belongsTo(Dialogue::class);
    }
}
