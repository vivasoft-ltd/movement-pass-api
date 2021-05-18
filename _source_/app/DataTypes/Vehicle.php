<?php


namespace App\DataTypes;


class Vehicle
{
    use DataTypeBasicOperations;

    public bool $selfDrive;

    public string $number;

    public ?Driver $driver;
}
