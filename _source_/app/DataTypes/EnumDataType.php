<?php

namespace App\DataTypes;


use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;
use RuntimeException;
use Throwable;

abstract class EnumDataType
{
    protected string $key;

    public static function getClassSignature(): string
    {
        return static::class;
    }

    public function setKey($key): static
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public function value()
    {
        $constantsAsArray = self::toArray();

        return $constantsAsArray[$this->key];
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws ReflectionException
     * @throws Throwable
     */
    public static function __callStatic(string $name, array $arguments)
    {
        $constantsAsArray = self::toArray();

        throw_unless(isset($constantsAsArray[strtoupper($name)]), RuntimeException::class, sprintf('You are trying to access %s which is not exists.', $name));

        return (new static())->setKey(strtoupper($name));
    }

    /**
     * @param $value
     * @return self
     */
    public static function make($value): self
    {
        return static::$value();
    }

    /**
     * @throws ReflectionException
     */
    public static function toArray(): array
    {
        $self = new ReflectionClass(static::getClassSignature());

        return $self->getConstants(ReflectionClassConstant::IS_PRIVATE);
    }
}
