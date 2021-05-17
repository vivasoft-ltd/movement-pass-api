<?php

namespace App\DataTypes;

/**
 * Class Trip
 * @package App\DataTypes
 *
 * @method static ROUND()
 * @method static ONEWAY()
 */
class Trip extends EnumDataType
{
    private const ROUND    = 'round';
    private const ONEWAY   = 'oneway';
}
