<?php

namespace App\Modules\Analysis\DTO;

/**
 * Одно найденное правилом событие. Критичность и код правила
 * добавит анализатор из настроек Rule — правило отвечает только за детект.
 */
final class RuleEventDTO
{
    /**
     * @param  string  $description  Что произошло (человекочитаемо).
     * @param  array<string, mixed>  $context  На что опирается вывод (id сообщений, метрики).
     */
    public function __construct(
        public string $description,
        public array $context = [],
    ) {}
}
