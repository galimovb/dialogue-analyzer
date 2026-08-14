<?php

namespace App\Modules\Analysis\Contracts;

use App\Modules\Analysis\DTO\RuleEventDTO;
use App\Modules\Analysis\Enums\Severity;
use App\Modules\Dialogues\Models\Dialogue;

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
