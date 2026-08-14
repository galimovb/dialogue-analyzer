<?php

namespace App\Modules\Rules\Enums;

enum Severity: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';

    /**
     * Вес для сортировки событий (сначала самые критичные).
     */
    public function weight(): int
    {
        return match ($this) {
            self::Low => 1,
            self::Medium => 2,
            self::High => 3,
        };
    }
}
