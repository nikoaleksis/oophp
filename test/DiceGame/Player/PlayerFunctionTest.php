<?php

namespace Niko\DiceGame\Player;

use PHPUnit\Framework\TestCase;

class PlayerFunctionTest extends TestCase
{
    /**
     *  Create object and test that the object has the expected values.
     */
    public function testRollingOfDices()
    {
        $isAI = false;
        $name = "Niko";
        $amountOfDices = 2;

        $player = new Player($isAI, $amountOfDices, $name);

        $this->assertEquals(0, $player->getScore());

        $player->rollDice();

        $this->assertGreaterThan(0, $player->getScore());
    }

    /**
     *  Tests that the last throw array is reset when function is called.
     */
    public function testResetLastScore()
    {
        $isAI = false;
        $name = "Niko";
        $amountOfDices = 2;

        $player = new Player($isAI, $amountOfDices, $name);

        $player->rollDice();
        $this->assertCount(2, $player->getLastThrow());

        $player->resetLastThrow();
        $this->assertCount(0, $player->getLastThrow());
    }
}
