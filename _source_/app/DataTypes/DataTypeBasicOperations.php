<?php

namespace App\DataTypes;



trait DataTypeBasicOperations
{
    public static function create(array $data): self
    {
        $self = new self();

        foreach ($data as $key => $value) {
            $self->{$key} = $value;
        }

        return $self;
    }
}
