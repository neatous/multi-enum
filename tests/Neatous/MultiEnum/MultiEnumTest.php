<?php declare(strict_types = 1);

namespace Neatous\MultiEnum;

use PHPUnit\Framework\TestCase;

class MultiEnumTest extends TestCase
{

    public function testMultiEnumFromIntBackedEnum(): void
    {
        $colours = Colors::fromEnumValuesArray([1, 4, 16]);
        $this->assertEquals(21, $colours->getValue());
        $this->assertEquals([1, 4, 16], $colours->toEnumValuesArray());
        $this->assertEquals(
            [
                Color::BLACK,
                Color::RED,
                Color::BLUE,
            ],
            $colours->toEnumsArray()
        );
        $this->assertTrue($colours->containsEnumValue(4));
        $this->assertTrue($colours->containsEnum(Color::RED));
        $this->assertFalse($colours->containsEnumValue(8));
        $this->assertFalse($colours->containsEnum(Color::GREEN));
        $this->assertFalse($colours->isEmpty());
        $this->assertEquals(3, $colours->size());
        $this->assertEquals(
            [
                Color::BLACK,
                Color::RED,
                Color::BLUE,
                Color::YELLOW,
            ],
            $colours->addEnum(Color::YELLOW)->toEnumsArray()
        );
        $this->assertEquals(
            [
                Color::BLACK,
                Color::RED,
            ],
            $colours->removeEnum(Color::BLUE)->toEnumsArray()
        );
        $this->assertEquals(
            [
                Color::BLACK,
                Color::RED,
            ],
            Colors::fromEnumsArray([Color::BLACK, Color::BLACK, Color::RED])->toEnumsArray()
        );
    }

    public function testMultiEnumFromStringBackedEnum(): void
    {
        $cars = Cars::fromEnumValuesArray(['audi', 'citroen', 'vw']);
        $this->assertEquals(11, $cars->getValue());
        $this->assertEquals(['audi', 'citroen', 'vw'], $cars->toEnumValuesArray());
        $this->assertEquals(
            [
                Car::AUDI,
                Car::CITROEN,
                Car::VOLKSWAGEN,
            ],
            $cars->toEnumsArray()
        );
        $this->assertTrue($cars->containsEnumValue('citroen'));
        $this->assertTrue($cars->containsEnum(Car::VOLKSWAGEN));
        $this->assertFalse($cars->containsEnumValue('skoda'));
        $this->assertFalse($cars->containsEnum(Car::SKODA));
        $this->assertFalse($cars->isEmpty());
        $this->assertEquals(3, $cars->size());
        $this->assertEquals(
            [
                Car::AUDI,
                Car::CITROEN,
                Car::SKODA,
                Car::VOLKSWAGEN,
            ],
            $cars->addEnum(Car::SKODA)->toEnumsArray()
        );
        $this->assertEquals(
            [
                Car::AUDI,
                Car::VOLKSWAGEN,
            ],
            $cars->removeEnum(Car::CITROEN)->toEnumsArray()
        );
        $this->assertEquals(
            [
                Car::AUDI,
                Car::CITROEN,
            ],
            Cars::fromEnumsArray([Car::AUDI, Car::AUDI, Car::CITROEN])->toEnumsArray()
        );
    }

    public function testEnumCheck(): void
    {
        $this->expectException('\Exception');
        $this->expectExceptionMessage(
            'Enum Neatous\MultiEnum\Suit is not allowed in multi enum Neatous\MultiEnum\Colors.'
        );
        /** @phpstan-ignore-next-line */
        Colors::fromEnumsArray([Color::RED, Color::BLUE, Suit::CLUBS]);
    }

    public function testEnumValueCheck(): void
    {
        $this->expectException('\Exception');
        $this->expectExceptionMessage('Value 1024 is not valid for enum Neatous\MultiEnum\Color.');
        Colors::fromEnumValuesArray([1, 2, 1024]);
    }

    public function testEnumClassCheck(): void
    {
        $this->expectException('\Exception');
        $this->expectExceptionMessage(
            'To use the enum (Neatous\MultiEnum\Suit) in multi enum, all of its values must be powers of 2 or equivalent mapping should be specified in the convertEnumValueToValue method.'
        );
        Suits::fromEnumsArray([Suit::CLUBS]);
    }
}
