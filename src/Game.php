<?php

declare(strict_types=1);

namespace Tennis;

final class Game
{
    private bool $isWon = false;

    public function __construct(
        public readonly Player $player1,
        public readonly Player $player2,
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
        $ruleContext = new RuleContext(game: $this, player: $player);

        match(true) {
            $this->scoreboard()->isDeuce() => $player->setScore(Scoring::Advantage),
            $this->scoreboard()->isAdvantage() => (new AdvantageRule($ruleContext))->handle(),
            $player->score()->is(Scoring::Forty) => $player->awardWin(),
            default => $player->setScore($player->score()->next())
        };
    }
}