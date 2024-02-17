<?php

namespace App\Mappers;

use ReflectionClass;

abstract class Mapper
{
    public static function getAllConstants(): array
    {
        $reflection = new ReflectionClass(static::class);
        return $reflection->getConstants();
    }
}
