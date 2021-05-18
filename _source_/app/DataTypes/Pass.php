<?php


namespace App\DataTypes;


use Illuminate\Support\Carbon as Date;

class Pass
{
    use DataTypeBasicOperations;

    public Date $startDate;

    public int $duration;

    public string $reason;
}
