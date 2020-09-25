<?php

namespace Niko\DiceGame\GameRound;

use PHPUnit\Framework\TestCase;

class GameRoundCreateObjectTest extends TestCase
{
    /**
     *  Create object and test that the object has the expected values.
     */
    public function testCreateObjectTwoPlayerWithAI()
    {
        $amountOfPlayers = 2;
        $amountOfDices = 2;
        $amountOfAI = 1;
        $name = "Niko";

        $gameRound = new GameRound($amountOfPlayers, $amountOfDices, $amountOfAI, $name);
        $this->assertInstanceOf("\Niko\DiceGame\GameRound\GameRound", $gameRound);

        $this->assertCount($amountOfPlayers, $gameRound->getPlayerArray());
        $this->assertContainsOnlyInstancesOf("\Niko\DiceGame\Player\Player", $gameRound->getPlayerArray());

        $aiCount = 0;
        $playerArray = $gameRound->getPlayerArray();

        foreach ($playerArray as $value) {
            if ($value->isAI() == true) {
                $aiCount++;
            }
        }

        $this->assertEquals($amountOfAI, $aiCount);
    }
}
