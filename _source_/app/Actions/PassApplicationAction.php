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
            'trip' => $dto->trip->value(),
            'from' => $dto->from,
            'pass' => [
                'start_date' => $dto->pass->startDate->format('Y-m-d H:i:s'),
                'duration' => $dto->pass->duration,
                'reason' => $dto->pass->reason,
            ],
            'vehicle_enabled' => $dto->vehicleOptionEnabled,
            'vehicle' => [
                'self_drive' => $dto->vehicle->selfDrive ?? null,
                'number' => $dto->vehicle->number ?? null,
                'driver' => [
                    'name' => $dto->vehicle->driver->name ?? null,
                    'licence' => $dto->vehicle->driver->licence ?? null
                ]
            ],
            'approved' => false,
        ];

        return $dto->user->applications()->create($data);
    }
}
