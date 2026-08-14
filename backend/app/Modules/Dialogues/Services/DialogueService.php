<?php

namespace App\Modules\Dialogues\Services;

use App\Modules\Dialogues\Models\Dialogue;
use App\Modules\Dialogues\Models\Message;
use App\Modules\Users\Enums\UserRole;
use App\Modules\Users\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class DialogueService
{
    /**
     * Список диалогов, видимых пользователю: админ — все, менеджер и клиент — свои.
     * Доступ к отдельному диалогу проверяет DialoguePolicy; здесь — фильтр коллекции.
     *
     * @return LengthAwarePaginator<int, Dialogue>
     */
    public function list(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return Dialogue::query()
            ->when(
                $user->role === UserRole::Manager,
                fn (Builder $query) => $query->where('manager_id', $user->id),
            )
            ->when(
                $user->role === UserRole::Client,
                fn (Builder $query) => $query->where('client_id', $user->id),
            )
            ->with(['manager', 'client'])
            ->withCount('messages')
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id): Dialogue
    {
        return Dialogue::query()
            ->with(['manager', 'client'])
            ->withCount('messages')
            ->findOrFail($id);
    }

    /**
     * Сообщения диалога, свежие первыми (для подгрузки ранних по кнопке).
     *
     * @return LengthAwarePaginator<int, Message>
     */
    public function messages(Dialogue $dialogue, int $perPage = 10): LengthAwarePaginator
    {
        return $dialogue->messages()
            ->reorder('sent_at', 'desc')
            ->with('sender')
            ->paginate($perPage);
    }
}
