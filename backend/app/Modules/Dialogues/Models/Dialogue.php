<?php

namespace App\Modules\Dialogues\Models;

use App\Modules\Dialogues\Enums\DialogueOutcome;
use App\Modules\Users\Models\User;
use Database\Factories\DialogueFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $manager_id
 * @property int $client_id
 * @property DialogueOutcome $outcome
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['manager_id', 'client_id', 'outcome'])]
class Dialogue extends Model
{
    /** @use HasFactory<DialogueFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'outcome' => DialogueOutcome::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Сообщения диалога в хронологическом порядке.
     *
     * @return HasMany<Message, $this>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('sent_at');
    }
}
