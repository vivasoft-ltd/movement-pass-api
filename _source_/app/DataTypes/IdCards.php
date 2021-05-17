<?php

namespace App\DataTypes;

/**
 * Class IdCards
 * @package App\DataTypes
 *
 * @method static NATIONAL_ID()
 * @method static DRIVING()
 * @method static PASSPORT()
 * @method static BIRTH_REGISTRATION()
 * @method static STUDENT_ID()
 * @method static EMPLOYEE_ID()
 */
class IdCards extends EnumDataType
{
    private const NATIONAL_ID = 'national_id';
    private const DRIVING = 'driving';
    private const PASSPORT = 'passport';
    private const BIRTH_REGISTRATION = 'birth_registration';
    private const STUDENT_ID = 'student_id';
    private const EMPLOYEE_ID = 'employee_id';
}
