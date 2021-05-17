<?php

namespace App\Http\Controllers;


use App\Actions\PassApplicationAction;
use App\DataTypes\Trip;
use App\DTO\PassDTO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Rules\OneApplicationPerDayRule;
use SwooleTW\Http\Helpers\Dumper;

class PassController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->applications
        );
    }

    public function store(Request $request, PassApplicationAction $passApplicationAction)
    {
        $this->validate($request, [
            'trip' => ['required', Rule::in(Trip::toArray())],
            'from' => 'required',
            'destination.location' => 'required',
            'destination.district' => 'required',
            'destination.upa_zilla' => 'required',
            'pass.start_date' => ['required', 'date', new OneApplicationPerDayRule($request->user())],
            'pass.duration' => ['required', 'between:1,12'],
            'pass.reason' => 'required',
            'vehicle_enabled' => ['required', 'boolean'],
            'vehicle.self_drive' => ['required', 'boolean'],
            'vehicle.number' => ['required'],
            'vehicle.driver.name' => [ Rule::requiredIf( function () use ($request) { !$request->input('vehicle.self_drive'); } ) ],
            'vehicle.driver.licence' => [ Rule::requiredIf( function () use ($request) { !$request->input('vehicle.self_drive'); } ) ],
        ]);

        return response()->json($passApplicationAction(PassDTO::createFromRequest($request)));
    }
}
