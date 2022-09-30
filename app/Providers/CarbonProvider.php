<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class CarbonProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::macro('dateName', function ($reduction = false, $day = true) {
            /** @var Carbon $this */
            $months = [
                "Января",
                "Февраля",
                "Марта",
                "Апреля",
                "Мая",
                "Июня",
                "Июля",
                "Августа",
                "Сентября",
                "Октября",
                "Ноября",
                "Декабря"
            ];
            return sprintf(
                $this->format(($day ? "\"d\" " : "") . "%\s Y %\s"),
                $months[$this->month - 1],
                ($reduction ? "г." : "года")
            );
        });
    }
}
