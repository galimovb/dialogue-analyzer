<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analysis_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dialogue_id')->constrained()->cascadeOnDelete();
            $table->string('rule_code');
            $table->string('severity');
            $table->text('description');
            $table->jsonb('context')->nullable();
            $table->timestamps();

            $table->index(['dialogue_id', 'severity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analysis_events');
    }
};
