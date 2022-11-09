<?php declare(strict_types = 1);

namespace Neatous\MultiEnum;

use BackedEnum;
use function assert;

/**
 * @template T of BackedEnum
 * @template U of int|string
 */
abstract class MultiEnum
{

    private int $value;

    /** @return class-string<T> */
    abstract protected static function getEnumClass(): string;

    final public function __construct(int $value)
    {
        static::validateEnumClass();
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    /** @return U[] */
    public function toEnumValuesArray(): array
    {
        $values = [];

        foreach (static::getEnumClass()::cases() as $case) {
            if ($this->containsEnum($case)) {
                /** @phpstan-var U $caseValue */
                $caseValue = $case->value;
                $values[] = $caseValue;
            }
        }

        return $values;
    }

    /** @return T[] */
    public function toEnumsArray(): array
    {
        $values = [];

        foreach (static::getEnumClass()::cases() as $case) {
            if ($this->containsEnum($case)) {
                $values[] = $case;
            }
        }

        return $values;
    }

    public function containsEnumValue(string|int $value): bool
    {
        $convertedEnumValue = static::convertEnumValueToValue($value);

        return ($convertedEnumValue & $this->getValue()) === $convertedEnumValue;
    }

    /** @param T $enum */
    public function containsEnum(BackedEnum $enum): bool
    {
        return $this->containsEnumValue($enum->value);
    }

    public function isEmpty(): bool
    {
        return $this->getValue() === 0;
    }

    /** @param T $enum */
    public function addEnum(BackedEnum $enum): static
    {
        return new static($this->getValue() | static::convertEnumValueToValue($enum->value));
    }

    /** @param T $enum */
    public function removeEnum(BackedEnum $enum): static
    {
        return new static($this->getValue() & ~static::convertEnumValueToValue($enum->value));
    }

    public function size(): int
    {
        return count($this->toEnumsArray());
    }

    /** @param U[] $values */
    public static function fromEnumValuesArray(array $values): static
    {
        $value = 0;

        foreach ($values as $enumValue) {
            static::validateEnumValue($enumValue);
            $value |= static::convertEnumValueToValue($enumValue);
        }

        return new static($value);
    }

    /** @param T[] $enums */
    public static function fromEnumsArray(array $enums): static
    {
        $value = 0;

        foreach ($enums as $enum) {
            static::validateEnum($enum);
            $value |= self::convertEnumToValue($enum);
        }

        return new static($value);
    }

    protected static function validateEnumValue(string|int $value): void
    {
        if (static::getEnumClass()::tryFrom($value) === null) {
            throw new \Exception(
                sprintf('Value %s is not valid for enum %s.', $value, static::getEnumClass())
            );
        }
    }

    protected static function validateEnum(BackedEnum $enum): void
    {
        $enumClass = static::getEnumClass();

        if (!$enum instanceof $enumClass) {
            throw new \Exception(
                sprintf('Enum %s is not allowed in multi enum %s.', $enum::class, static::class)
            );
        }
    }

    protected static function validateEnumClass(): void
    {
        foreach (static::getEnumClass()::cases() as $enum) {
            if (!self::isPositivePowerOfTwo(self::convertEnumToValue($enum))) {
                throw new \Exception(
                    sprintf(
                        'To use the enum (%s) in multi enum, all of its values must be powers of 2 or equivalent mapping should be specified in the convertEnumValueToValue method.',
                        static::getEnumClass(),
                    )
                );
            }
        }
    }

    protected static function convertEnumValueToValue(string|int $enumValue): int
    {
        assert(
            is_int($enumValue),
            new \Exception(
                sprintf(
                    'To use the string backed enum (%s) in multi enum, mapping should be specified in the convertEnumValueToValue method.',
                    static::getEnumClass(),
                ),
            ),
        );

        return $enumValue;
    }

    /** @param T $enum */
    private static function convertEnumToValue(BackedEnum $enum): int
    {
        return static::convertEnumValueToValue($enum->value);
    }

    private static function isPositivePowerOfTwo(int $value): bool
    {
        if ($value < 1) {
            return false;
        }

        return ($value & $value - 1) === 0;
    }
}
