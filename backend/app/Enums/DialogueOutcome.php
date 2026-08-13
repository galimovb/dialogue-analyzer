<?php

namespace App\Enums;

enum DialogueOutcome: string
{
    case Purchased = 'purchased';
    case NotPurchased = 'not_purchased';
}
