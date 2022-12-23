<?php

declare(strict_types=1);

namespace Tennis;

final class Game
{
    private bool $isWon = false;

    public function __construct(
        private readonly Player $player1,
        private readonly Player $player2,
    ) {
    }

    public function scores(Player $player): string
    {
        $this->isWon ?: $this->processScore($player);

        return $this->scoreboard()->standings();
    }

    public function scoreboard(): Scoreboard
    {
        return new Scoreboard($this->player1, $this->player2);
    }

    private function processScore(Player $player): void
    {
        // @todo refactor to different strategy classes
        $advantageHandler = function () use ($player) {
            $player->score()->is(Scoring::Advantage)
                ? $player->awardWin()
                : $player->setScore($player->score()->next());

            $this->player1 === $player
                ? $this->player2->setScore(Scoring::Forty)
                : $this->player1->setScore(Scoring::Forty);
        };

        match(true) {
            $this->scoreboard()->isDeuce() => $player->setScore(Scoring::Advantage),
            $this->scoreboard()->isAdvantage() => $advantageHandler(),
            $player->score()->is(Scoring::Forty) => $player->awardWin(),
            default => $player->setScore($player->score()->next())
        };
    }
}