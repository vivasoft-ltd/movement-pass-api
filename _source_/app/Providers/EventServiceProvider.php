<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\Registered::class => [
            \App\Listeners\VerifyPhone::class,
        ],
        \App\Events\LoggedIn::class => [
            \App\Listeners\TokenSave::class,
        ],
    ];
}
