<?php

namespace App\Providers;


use App\Domain\Contracts\Academic\DisciplinaClientInterface;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Domain\Contracts\Academic\GraduationClientInterface;
use App\Infrastructure\Http\Academic\Disciplina;
use App\Infrastructure\Http\Academic\GraduationClient;
use App\Infrastructure\Http\Assessment\Factories\AiProviderFactory;
use App\Infrastructure\Http\Assessment\Factories\ExamClientFactory;
use App\Infrastructure\Http\FaculdadeHttpClient;
use App\Infrastructure\Http\Session\FaculdadeSession;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FaculdadeSession::class);

        $this->app->singleton(FaculdadeClientInterface::class, FaculdadeHttpClient::class);

        $this->app->singleton(AiProviderFactory::class);

        $this->app->singleton(ExamClientFactory::class);

        $this->app->bind(GraduationClientInterface::class, GraduationClient::class);

        $this->app->bind(DisciplinaClientInterface::class, Disciplina::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    protected function configureDefaults(): void
    {
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
            : null
        );
    }
}
