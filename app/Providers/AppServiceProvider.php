<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

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
        Table::configureUsing(function (Table $table): void {
            $table
                ->filtersLayout(FiltersLayout::AboveContentCollapsible)
                ->paginationPageOptions([10, 25, 50]);
        });
    }
}
