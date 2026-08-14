<?php

namespace App\Modules\Dialogues\Enums;

enum DialogueOutcome: string
{
    case Purchased = 'purchased';
    case NotPurchased = 'not_purchased';
}
