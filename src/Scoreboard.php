<?php

declare(strict_types=1);

namespace Tennis;

use function sprintf;

final class Scoreboard
{
    public function __construct(
        private readonly Player $player1,
        private readonly Player $player2,
    ) {
    }

    public function standings(): string
    {
        if ($this->player1->hasWonGame()) {
            return 'Player 1 has won the game!';
        }

        if ($this->player2->hasWonGame()) {
            return 'Player 2 has won the game!';
        }

        if ($this->player1->score()->is(Scoring::Advantage)) {
            return 'Advantage Player 1';
        }

        if ($this->player2->score()->is(Scoring::Advantage)) {
            return 'Advantage Player 2';
        }

        if ($this->isDeuce()) {
            return 'Deuce';
        }

        if ($this->sameScoring()) {
            return sprintf('%s All', $this->player1->score()->name);
        }

        return $this->sameScoring()
            ? sprintf('%s All', $this->player1->score()->name)
            : sprintf('%s %s', $this->player1->score()->name, $this->player2->score()->name);
    }

    public function isDeuce(): bool
    {
        return $this->sameScoring() && $this->player1->score() === Scoring::Forty;
    }

    public function sameScoring(): bool
    {
        return $this->player1->score() === $this->player2->score();
    }

    public function isAdvantage(): bool
    {
        return $this->player1->score() === Scoring::Advantage
            || $this->player2->score() === Scoring::Advantage;
    }
}