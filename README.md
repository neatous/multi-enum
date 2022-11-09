<h1 align="center">
	<img src="docs/img/neatous.png" alt="Logo" height="120" />
	<br/>
	PHP multi enum
</h1>

<p align="center">
    Multi enum support for the PHP 8.1+ enums.
</p>

## Usage

### Basic example

Let's say we have a standard enumeration, e.g. for Suits:

```php
<?php declare(strict_types = 1);

namespace Neatous\MultiEnum;

enum Suit: int
{
    case HEARTS = 1;
    case DIAMONDS = 2;
    case CLUBS = 4;
    case SPADES = 8;
}
```

Then it is easy to create a multi variant:

```php
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
```

### Enum values mapping

If the single enum values do not match the multi enum values, you can map them to the multi enum values.

```php
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
```

## Versions

| State  | Version      | PHP     |
|--------|--------------|---------|
| stable | `1.0.0`      | `>=8.1` |
| dev    | `dev-master` | `>=8.1` |
