<?php

namespace App\Modules\Dialogues\Policies;

use App\Modules\Dialogues\Models\Dialogue;
use App\Modules\Users\Enums\UserRole;
use App\Modules\Users\Models\User;

/**
 * Доступ к конкретному диалогу (аналог Symfony Voter):
 * админ — к любому, менеджер — к своим, клиент — к своим.
 */
class DialoguePolicy
{
    public function view(User $user, Dialogue $dialogue): bool
    {
        return match ($user->role) {
            UserRole::Admin => true,
            UserRole::Manager => $dialogue->manager_id === $user->id,
            UserRole::Client => $dialogue->client_id === $user->id,
        };
    }
}
