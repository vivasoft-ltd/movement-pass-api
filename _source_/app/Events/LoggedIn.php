<?php

namespace App\Events;

use App\Models\MongoUserModel;
use Illuminate\Queue\SerializesModels;

class LoggedIn
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var MongoUserModel
     */
    public $user;

    /**
     * @var string
     */
    public $token;

    /**
     * Create a new event instance.
     *
     * @param MongoUserModel $user
     * @param $token string
     */
    public function __construct(MongoUserModel $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }
}
