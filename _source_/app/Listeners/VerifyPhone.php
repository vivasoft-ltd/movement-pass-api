<?php

namespace App\Listeners;

use App\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class VerifyPhone implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Registered  $registered
     * @return void
     */
    public function handle(Registered $registered)
    {
        $user = $registered->user;

        $user->update([
            'code' => mt_rand(1111, 9999),
        ]);

        //@todo - send code via SMS gate
    }
}
