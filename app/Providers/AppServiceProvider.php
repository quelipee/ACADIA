<?php

namespace App\Providers;

use App\Console\Commands\Test;
use App\Domain\Contracts\AIClientInterface;
use App\Domain\Contracts\FaculdadeClientInterface;
use App\Domain\Contracts\Academic\GraduationClientInterface;
use App\Domain\Contracts\Academic\SubjectClientInterface;
use App\Domain\Contracts\Assessment\ExamClientInterface;
use App\Infrastructure\Http\Academic\GraduationClient;
use App\Infrastructure\Http\Academic\SubjectClient;
use App\Infrastructure\Http\Assessment\ApolClient;
use App\Infrastructure\Http\Assessment\ExamClient;
use App\Infrastructure\Http\FaculdadeHttpClient;
use App\Infrastructure\Http\OpenAIClient;
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

        $this->app->singleton(AIClientInterface::class, OpenAIClient::class);

        $this->app->when(Test::class)
        ->needs(ExamClientInterface::class)
        ->give(ApolClient::class);

        $this->app->when(Test::class)
        ->needs(ExamClientInterface::class)
        ->give(ExamClient::class);

        $this->app->bind(GraduationClientInterface::class, GraduationClient::class);

        $this->app->bind(SubjectClientInterface::class, SubjectClient::class);
        
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
