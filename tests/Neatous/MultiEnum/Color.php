<?php declare(strict_types = 1);

namespace Neatous\MultiEnum;

enum Color: int
{
    case BLACK = 1;
    case WHITE = 2;
    case RED = 4;
    case GREEN = 8;
    case BLUE = 16;
    case CYAN = 32;
    case MAGENTA = 64;
    case YELLOW = 128;
}
