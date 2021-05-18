<?php

namespace App\DTO;


use ReflectionClass;
use ReflectionProperty;

abstract class DTO
{
    public static function getClassSignature(): string
    {
        return static::class;
    }

    /**
     * @throws \ReflectionException
     */
    public static function self(array $data): static
    {
        $reflect = new ReflectionClass(static::getClassSignature());
        $properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

        $self = new (static::getClassSignature());

        foreach ($properties as $property) {
            if (isset($data[$property->getName()])) {
                $self->{$property->getName()} = $data[$property->getName()];
            }
        }

        return $self;
    }

    /**
     * @throws \ReflectionException
     */
    public function toArray(): array
    {
        $arrResponse = [];

        $reflect = new ReflectionClass(static::getClassSignature());
        $properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            $arrResponse[$property->getName()] = $this->{$property->getName()} ?? null;
        }

        return $arrResponse;
    }
}
