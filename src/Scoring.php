<?php

declare(strict_types=1);

namespace Tennis;

enum Scoring: int
{
    case Love = 0;
    case Fifteen = 1;
    case Thirty = 2;
    case Forty = 3;
    case Advantage = 4;

    public function is(self $comparison): bool
    {
        return $this === $comparison;
    }

    public function next(): self
    {
        $currentValue = $this->value;
        $newValue = $this !== self::Advantage
            ? self::cases()[++$currentValue]
            : $this;

        return $newValue === self::Advantage
            ? self::Forty
            : $newValue;
    }
}