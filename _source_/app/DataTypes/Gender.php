<?php

namespace App\DataTypes;

/**
 * Class Gender
 * @package App\DataTypes
 *
 * @method static MALE()
 * @method static FEMALE()
 * @method static OTHER()
 */
class Gender extends EnumDataType
{
    private const MALE      = 'male';
    private const FEMALE    = 'female';
    private const OTHER     = 'other';
}
