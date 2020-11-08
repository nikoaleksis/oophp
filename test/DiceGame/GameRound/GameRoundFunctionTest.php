<?php

namespace Niko\DiceGame\GameRound;

use PHPUnit\Framework\TestCase;

class GameRoundFunctionTest extends TestCase
{
    protected function initiateObject()
    {
        $amountOfPlayers = 2;
        $amountOfDices = 2;
        $amountOfAI = 1;
        $name = "Niko";

        return new GameRound($amountOfPlayers, $amountOfDices, $amountOfAI, $name);
    }
    /**
    *  Checks if the player array contains a winner.
    */
    public function testCheckForWinner()
    {
        $gameRound = $this->initiateObject();

        $player = $gameRound->getPlayerArray()[0];
        $player->setScore(100);

        $this->assertTrue($gameRound->checkForWinner());
    }

    /**
    *  Checks if the function that advances the round counter.
    */
    public function testIncrementToNextRound()
    {
        $gameRound = $this->initiateObject();
        $firstValue = 0;
        $secondValue = 1;

        $this->assertTrue($gameRound->getCurrentRound() === $firstValue);
        $gameRound->incrementToNextRound();
        $this->assertTrue($gameRound->getCurrentRound() === $secondValue);
    }


    /**
    * Tests that the function returns a player.
    */
    public function testGetPlayerByName()
    {
        $gameRound = $this->initiateObject();

        $player = $gameRound->getPlayerByName("Niko");

        $this->assertInstanceOf("\Niko\DiceGame\Player\Player", $player);

        $this->assertNull($gameRound->getPlayerByName("Nothing At All"));
    }

    /**
     * Tests that the static variable MAX_SCORE has the right value.
     */
    // public function testGetMaxScore()
    // {
    //     $this->assertEquals(100, GameRound::getMaxScore());
    // }

    /**
     * Tests that the function returns an array.
     */
    public function testGetGameRoundHistory()
    {
        $gameRound = $this->initiateObject();

        $this->assertIsArray($gameRound->getGameRoundHistory());
    }

    /**
     * Tests that the right amount of players are playing the game.
     */
    public function testGetAmountOfPlayers()
    {
        $gameRound = $this->initiateObject();

        $this->assertEquals(2, $gameRound->getAmountOfPlayers());
    }

    /**
     * Tests that the right amount of dices are set up.
     */
    public function testAmountOfDices()
    {
        $gameRound = $this->initiateObject();

        $this->assertEquals(2, $gameRound->getAmountOfDices());
    }

    /**
     * Tests that the game recognizes who the current player is.
     */
    public function testCurrentPlayerName()
    {
        $gameRound = $this->initiateObject();

        $this->assertEquals("Niko", $gameRound->CurrentPlayerName());
    }
}
