<?php

namespace App\Providers;

use App\Modules\Analysis\Listeners\ReanalyzeDialoguesOnRuleUpdated;
use App\Modules\Dialogues\Models\Dialogue;
use App\Modules\Dialogues\Policies\DialoguePolicy;
use App\Modules\Rules\Events\RuleUpdated;
use Carbon\CarbonImmutable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureDefaults();

        // Кросс-модульная связка (композиционный корень знает про все модули):
        Gate::policy(Dialogue::class, DialoguePolicy::class);
        Event::listen(RuleUpdated::class, ReanalyzeDialoguesOnRuleUpdated::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        // У нас единый конверт ApiResponse — двойная обёртка "data" не нужна.
        JsonResource::withoutWrapping();

        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
