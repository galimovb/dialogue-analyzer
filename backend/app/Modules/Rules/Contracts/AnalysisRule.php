<?php

namespace App\Modules\Rules\Contracts;

use App\Modules\Dialogues\Models\Dialogue;
use App\Modules\Rules\DTO\RuleEventDTO;
use App\Modules\Rules\Enums\Severity;

interface AnalysisRule
{
    /**
     * Уникальный код правила (совпадает с Rule.code в БД).
     */
    public function code(): string;

    /**
     * Человекочитаемое название — для сидинга и раздела «Правила».
     */
    public function name(): string;

    /**
     * Критичность по умолчанию (в БД её можно переопределить).
     */
    public function defaultSeverity(): Severity;

    /**
     * Настройки по умолчанию (пороги) — для первичного сидинга.
     *
     * @return array<string, mixed>
     */
    public function defaultConfig(): array;

    /**
     * Проанализировать диалог и вернуть найденные события.
     *
     * @param  array<string, mixed>  $config  Актуальные настройки из БД.
     * @return array<RuleEventDTO>
     */
    public function analyze(Dialogue $dialogue, array $config): array;
}
