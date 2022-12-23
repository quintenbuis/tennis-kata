<?php

declare(strict_types=1);

namespace Tennis;

final class Player
{
    private Scoring $scoring;
    private int $matchesWon = 0;

    public function __construct()
    {
        $this->scoring = Scoring::Love;
    }

    public function setScore(Scoring $scoring): void
    {
        $this->scoring = $scoring;
    }

    public function score(): Scoring
    {
        return $this->scoring;
    }

    public function awardWin(): void
    {
        $this->matchesWon++;
    }

    public function hasWonGame(): bool
    {
        return $this->matchesWon > 0;
    }
}