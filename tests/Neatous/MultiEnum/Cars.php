<?php declare(strict_types = 1);

namespace Neatous\MultiEnum;

/** @extends MultiEnum<Car, string> */
class Cars extends MultiEnum
{
    public static function getEnumClass(): string
    {
        return Car::class;
    }

    protected static function convertEnumValueToValue(string|int $enumValue): int
    {
        return match ($enumValue) {
            Car::AUDI->value => 1,
            Car::CITROEN->value => 2,
            Car::SKODA->value => 4,
            Car::VOLKSWAGEN->value => 8,
            default => throw new \Exception(
                sprintf('Mapping missing for the single enum value "%s".', $enumValue)
            ),
        };
    }
}
