<?php


namespace App\Actions;


use App\DTO\PassDTO;
use App\Models\Application;
use SwooleTW\Http\Helpers\Dumper;

class PassApplicationAction
{
    public function __invoke(PassDTO $dto)
    {
        $data = [
            'user' => $dto->user->toArray(),
            'trip' => $dto->trip->value(),
            'from' => $dto->from,
            'pass' => [
                'startDate' => $dto->pass->startDate->format('Y-m-d H:i:s'),
                'duration' => $dto->pass->duration,
                'reason' => $dto->pass->reason,
            ],
            'vehicleOptionEnabled' => $dto->vehicleOptionEnabled,
            'vehicle' => [
                'selfDrive' => $dto->vehicle->selfDrive ?? null,
                'number' => $dto->vehicle->number ?? null,
                'driver' => [
                    'name' => $dto->vehicle->driver->name ?? null,
                    'licence' => $dto->vehicle->driver->licence ?? null
                ]
            ]
        ];

        return Application::create($data);
    }
}
