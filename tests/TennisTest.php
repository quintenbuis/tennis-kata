<?php

declare(strict_types=1);

namespace Tests;

use Generator;
use PHPUnit\Framework\TestCase;
use Tennis\Game;
use Tennis\Player;
use Tennis\Scoring;

/**
 * @covers \Tennis\Game
 */
final class TennisTest extends TestCase
{
    public Player $player1;
    public Player $player2;
    public Game $game;

    protected function setUp(): void
    {
        $this->player1 = new Player();
        $this->player2 = new Player();

        $this->game = new Game($this->player1, $this->player2);
    }

    public function test_a_player_can_score(): void
    {
        $this->game->scores($this->player1);

        self::assertEquals(Scoring::Fifteen, $this->player1->score());
    }

    /**
     * @dataProvider scores
     */
    public function test_scoreboard_return_correct_values(
        Scoring $player1Score,
        Scoring $player2Score,
        string $expected,
    ): void
    {
        $this->player1->setScore($player1Score);
        $this->player2->setScore($player2Score);

        self::assertEquals($expected, $this->game->scoreboard()->standings());
    }

    public function test_a_player_can_win_the_game(): void
    {
        $this->player1->setScore(Scoring::Forty);

        $this->game->scores($this->player1);

        self::assertTrue($this->player1->hasWonGame());
        self::assertSame('Player 1 has won the game!', $this->game->scoreboard()->standings());
    }

    public function test_it_recognizes_advantages(): void
    {
        $this->player1->setScore(Scoring::Advantage);
        $this->player2->setScore(Scoring::Forty);

        self::assertTrue($this->game->scoreboard()->isAdvantage());
    }

    public function test_it_goes_into_advantage_when_its_deuce(): void
    {
        $this->player1->setScore(Scoring::Forty);
        $this->player2->setScore(Scoring::Forty);
        self::assertFalse($this->game->scoreboard()->isAdvantage());

        $this->game->scores($this->player1);

        self::assertTrue($this->game->scoreboard()->isAdvantage());
        self::assertTrue($this->player1->score()->is(Scoring::Advantage));
    }

    public function test_a_advantage_can_go_back_to_deuce(): void
    {
        $this->player1->setScore(Scoring::Forty);
        $this->player2->setScore(Scoring::Advantage);

        $this->game->scores($this->player1);

        self::assertTrue($this->game->scoreboard()->isDeuce());
    }

    public function scores(): Generator
    {
        yield 'Love All' => [
            'player1' => Scoring::Love,
            'player2' => Scoring::Love,
            'expected' => 'Love All'
        ];

        yield 'Fifteen Love' => [
            'player1' => Scoring::Fifteen,
            'player2' => Scoring::Love,
            'expected' => 'Fifteen Love'
        ];

        yield 'Fifteen All' => [
            'player1' => Scoring::Fifteen,
            'player2' => Scoring::Fifteen,
            'expected' => 'Fifteen All'
        ];

        yield 'Thirty Fifteen' => [
            'player1' => Scoring::Thirty,
            'player2' => Scoring::Fifteen,
            'expected' => 'Thirty Fifteen'
        ];

        yield 'Thirty All' => [
            'player1' => Scoring::Thirty,
            'player2' => Scoring::Thirty,
            'expected' => 'Thirty All'
        ];

        yield 'Forty Thirty' => [
            'player1' => Scoring::Forty,
            'player2' => Scoring::Thirty,
            'expected' => 'Forty Thirty'
        ];

        yield 'Deuce' => [
            'player1' => Scoring::Forty,
            'player2' => Scoring::Forty,
            'expected' => 'Deuce'
        ];

        yield 'Advantage Player 1' => [
            'player1' => Scoring::Advantage,
            'player2' => Scoring::Forty,
            'expected' => 'Advantage Player 1'
        ];

        yield 'Advantage Player 2' => [
            'player1' => Scoring::Forty,
            'player2' => Scoring::Advantage,
            'expected' => 'Advantage Player 2'
        ];
    }
}
