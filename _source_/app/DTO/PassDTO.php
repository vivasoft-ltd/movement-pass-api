<?php


namespace App\DTO;


use App\DataTypes\Destination;
use App\DataTypes\Driver;
use App\DataTypes\Pass;
use App\DataTypes\Trip;
use App\DataTypes\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PassDTO extends DTO
{
    public User $user;

    public Trip $trip;

    public string $from;

    public Destination $destination;

    public Pass $pass;

    public bool $vehicleOptionEnabled;

    public ?Vehicle $vehicle;

    public static function createFromRequest(Request $request)
    {
        $data = [
            'user' => $request->user(),
            'trip' => Trip::make($request->input('trip')),
            'from' => $request->input('from'),
            'destination' => Destination::create([
                'location' => $request->input('destination.location'),
                'district' => $request->input('destination.district'),
                'upaZilla' => $request->input('destination.upa_zilla'),
            ]),
            'pass' => Pass::create([
                'startDate' => Carbon::parse($request->input('pass.start_date')),
                'duration' => $request->input('pass.duration'),
                'reason' => $request->input('pass.reason'),
            ]),
            'vehicleOptionEnabled' => $request->input('vehicle_enabled'),
        ];

        if ($data['vehicleOptionEnabled']) {
            $data['vehicle'] = Vehicle::create([
                'selfDrive' => $request->input('vehicle.self_drive'),
                'number' => $request->input('vehicle.number'),
                'driver' => $request->input('vehicle.self_drive') === false ? Driver::create([
                    'name' => $request->input('vehicle.driver.name'),
                    'licence' => $request->input('vehicle.driver.licence'),
                ]) : null,
            ]);
        }

        return static::self($data);
    }
}
