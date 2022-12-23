<?php

declare(strict_types=1);

namespace Tennis;

final class AdvantageRule
{
    public function __construct(
        public readonly RuleContext $context
    )
    {
    }

    public function handle(): void
    {
        $player = $this->context->player;

        $player->score()->is(Scoring::Advantage)
            ? $player->awardWin()
            : $player->setScore($player->score()->next());

        $this->context->game->player1 === $player
            ? $this->context->game->player2->setScore(Scoring::Forty)
            : $this->context->game->player1->setScore(Scoring::Forty);
    }
}