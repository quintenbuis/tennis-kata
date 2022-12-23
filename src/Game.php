<?php

declare(strict_types=1);

namespace Tennis;

use function in_array;

final class Game
{
    private bool $isWon = false;

    public function __construct(
        private Player $player1,
        private Player $player2,
    ) {
    }

    public function scores(Player $player): string
    {
        $this->processScore($player);

        return $this->scoreboard()->standings();
    }

    public function scoreboard(): Scoreboard
    {
        return new Scoreboard($this->player1, $this->player2);
    }

    public function winner(Player $player): void
    {
        $player->awardWin();
    }

    private function processScore(Player $player): void
    {
        if ($this->isWon) {
            return;
        }

        if ($this->scoreboard()->isDeuce()) {
            $player->setScore(Scoring::Advantage);

            return;
        }

        if ($this->scoreboard()->isAdvantage()) {
            $player->score()->is(Scoring::Advantage)
                ? $player->awardWin()
                : $player->setScore(
                    $player->score()->next()
            );

            $this->player1 === $player
                ? $this->player2->setScore(Scoring::Forty)
                : $this->player1->setScore(Scoring::Forty);

            return;
        }

        if ($player->score()->is(Scoring::Forty)) {
            $player->awardWin();

            return;
        }

        $player->setScore(
            $player->score()->next()
        );
    }
}