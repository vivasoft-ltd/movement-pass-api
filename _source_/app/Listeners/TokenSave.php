<?php

namespace App\Listeners;

use App\Events\LoggedIn;
use App\Models\AccessToken;

class TokenSave
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
     * @param LoggedIn $loggedIn
     * @return void
     */
    public function handle(LoggedIn $loggedIn)
    {
        $decodedToken = app('tymon.jwt.provider.jwt')->decode($loggedIn->token);
        $user = $loggedIn->user;

        $decodedToken['iss'] = get_class($user);
        $decodedToken['active'] = true;

        $model = new AccessToken($decodedToken);
        $model->save();
    }
}
