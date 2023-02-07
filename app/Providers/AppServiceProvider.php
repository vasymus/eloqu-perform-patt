<?php

namespace App\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('orderByNullsLast', function ($column, $direction = 'asc') {
            /** @var \Illuminate\Database\Query\Builder $this */
            $columnWrapped = $this->getGrammar()->wrap($column);
            $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';

            return $this
                ->orderByRaw(sprintf('%s is null', $columnWrapped))
                ->orderBy($column, $direction);
        });
    }
}
