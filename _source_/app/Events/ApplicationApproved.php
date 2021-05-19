<?php

namespace App\Events;

use App\Models\Application;

class ApplicationApproved extends Event
{
    public $application;

    /**
     * Create a new event instance.
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }
}
