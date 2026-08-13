<?php

namespace App\Services;

use App\Models\Dialogue;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DialogueService
{
    public function list(int $perPage = 10): LengthAwarePaginator
    {
        return Dialogue::query()
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
     */
    public function messages(int $id, int $perPage = 10): LengthAwarePaginator
    {
        $dialogue = Dialogue::query()->findOrFail($id);

        return $dialogue->messages()
            ->reorder('sent_at', 'desc')
            ->with('sender')
            ->paginate($perPage);
    }
}
