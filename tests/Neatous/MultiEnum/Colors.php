<?php declare(strict_types = 1);

namespace Neatous\MultiEnum;

/** @extends MultiEnum<Color, int> */
class Colors extends MultiEnum
{
    public static function getEnumClass(): string
    {
        return Color::class;
    }
}
