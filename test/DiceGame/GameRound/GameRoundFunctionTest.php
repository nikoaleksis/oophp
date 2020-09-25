<?php

namespace Niko\DiceGame\GameRound;

use PHPUnit\Framework\TestCase;

class GameRoundFunctionTest extends TestCase
{
    /**
     *  Checks if the player array contains a winner.
     */
    public function testCheckForWinner()
    {
        $amountOfPlayers = 2;
        $amountOfDices = 2;
        $amountOfAI = 1;
        $name = "Niko";

        $gameRound = new GameRound($amountOfPlayers, $amountOfDices, $amountOfAI, $name);

        $player = $gameRound->getPlayerArray()[0];
        $player->setScore(100);

        $this->assertTrue($gameRound->checkForWinner());
    }

    /**
     *  Checks if the function that advances the round counter.
     */
    public function testIncrementToNextRound()
    {
        $amountOfPlayers = 2;
        $amountOfDices = 2;
        $amountOfAI = 1;
        $name = "Niko";
        $firstValue = 0;
        $secondValue = 1;

        $gameRound = new GameRound($amountOfPlayers, $amountOfDices, $amountOfAI, $name);

        $this->assertTrue($gameRound->getCurrentRound() === $firstValue);
        $gameRound->incrementToNextRound();
        $this->assertTrue($gameRound->getCurrentRound() === $secondValue);
    }
}
