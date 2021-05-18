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
        $count = Application::where('user_id', $this->user->getIdAttribute())->where('approved', false)->count();

        return $count < 1;
    }

    public function message()
    {
        return 'You have already applied for an application.';
    }
}
