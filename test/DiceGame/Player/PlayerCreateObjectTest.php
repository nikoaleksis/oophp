<?php

namespace Niko\DiceGame\Player;

use PHPUnit\Framework\TestCase;

class PlayerCreateObjectTest extends TestCase
{
    /**
     *  Create object and test that the object has the expected values.
     */
    public function testCreateObjectNoAI()
    {
        $isAI = false;
        $name = "Niko";
        $amountOfDices = 2;

        $player = new Player($isAI, $amountOfDices, $name);
        $this->assertInstanceOf("\Niko\DiceGame\Player\Player", $player);

        $this->assertCount($amountOfDices, $player->getDiceArray());
        $this->assertContainsOnlyInstancesOf("\Niko\DiceGame\Dice\Dice", $player->getDiceArray());

        $this->assertFalse($player->IsAI());
    }
}
