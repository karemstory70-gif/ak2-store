<?php

namespace App\Providers;

use App\Models\StorefrontPage;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        View::composer('layouts.store', function (\Illuminate\View\View $view): void {
            $storefrontPagesAvailable = Schema::hasTable((new StorefrontPage)->getTable());

            $globalPage = $storefrontPagesAvailable
                ? StorefrontPage::query()
                    ->where('slug', 'global')
                    ->where('is_published', true)
                    ->first()
                : null;

            $publishedNavigationPages = $storefrontPagesAvailable
                ? StorefrontPage::query()
                    ->select(['slug', 'title', 'translations'])
                    ->where('is_published', true)
                    ->where('slug', '!=', 'global')
                    ->where('slug', '!=', 'home')
                    ->orderBy('title')
                    ->get()
                : collect();

            $globalSections = collect($globalPage?->sections ?? [])
                ->filter(fn ($section): bool => is_array($section) && (bool) ($section['is_active'] ?? true))
                ->keyBy('key')
                ->all();

            $view->with('globalSections', $globalSections);
            $view->with('publishedNavigationPages', $publishedNavigationPages);
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
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
            : null,
        );
    }
}
