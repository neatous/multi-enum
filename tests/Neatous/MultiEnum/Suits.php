<?php declare(strict_types = 1);

namespace Neatous\MultiEnum;

/** @extends MultiEnum<Suit, int> */
class Suits extends MultiEnum
{
    public static function getEnumClass(): string
    {
        return Suit::class;
    }
}
