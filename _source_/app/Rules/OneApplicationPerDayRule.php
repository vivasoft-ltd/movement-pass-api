<?php


namespace App\Rules;


use App\Models\Application;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;
use SwooleTW\Http\Helpers\Dumper;

class OneApplicationPerDayRule implements Rule
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function passes($attribute, $value)
    {
        //@todo - Until we restart swoole the query always counting same.

        $count = $this->user
            ->applications
            ->whereBetween('created_at',[
                Carbon::today()->format('Y-m-d 00:00:00'),
                Carbon::today()->format('Y-m-d 23:59:59')
            ])->count();

        Log::debug($count);

        return $count < 1;
    }

    public function message()
    {
        return 'You have already applied for an application.';
    }
}
