<?php


namespace App\DataTypes;


class Destination
{
    use DataTypeBasicOperations;

    public string $location;

    public string $district;

    public string $upaZilla;
}
