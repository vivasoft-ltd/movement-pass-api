<?php

namespace App\Http\Controllers;


use App\Actions\PassApplicationAction;
use App\DataTypes\Trip;
use App\DTO\PassDTO;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SwooleTW\Http\Helpers\Dumper;

class PassController extends Controller
{
    public function store(Request $request, PassApplicationAction $passApplicationAction)
    {
        $this->validate($request, [
            'trip' => ['required', Rule::in(Trip::toArray())],
            'from' => 'required',
            'destination.location' => 'required',
            'destination.district' => 'required',
            'destination.upa_zilla' => 'required',
            'pass.start_date' => ['required', 'date'],
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
