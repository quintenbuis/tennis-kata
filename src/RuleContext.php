<?php

declare(strict_types=1);

namespace Tennis;

final class RuleContext
{
    public function __construct(
        public readonly Game $game,
        public readonly Player $player,
    )
    {
    }
}