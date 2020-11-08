<?php

namespace Niko\Histogram;

use PHPUnit\Framework\TestCase;
use Niko\DiceGame\GameRound\GameRound;

class HistogramFunctionTest extends TestCase
{
    /**
     *  Test that the inject data function works and inserts values.
     */
    public function testInjectData()
    {
        $amountOfPlayers = 2;
        $amountOfDices = 2;
        $amountOfAI = 1;
        $name = "Niko";

        $gameRound = new GameRound($amountOfPlayers, $amountOfDices, $amountOfAI, $name);
        $this->assertInstanceOf("\Niko\DiceGame\GameRound\GameRound", $gameRound);

        $gameRound->rollDices($name);

        $gameRound->resetPlayerLastThrowHistory();

        $histogram = new Histogram();
        $this->assertInstanceOf("\Niko\Histogram\Histogram", $histogram);

        $histogram->injectData($gameRound);

        $this->assertGreaterThan(0, count($histogram->getSequence()));
    }

    // /**
    //  *  Tests that the function returns a histogram with values.
    //  */
    // public function testGetAsText()
    // {
    //     $amountOfPlayers = 2;
    //     $amountOfDices = 2;
    //     $amountOfAI = 1;
    //     $name = "Niko";
    //
    //     $gameRound = new GameRound($amountOfPlayers, $amountOfDices, $amountOfAI, $name);
    //     $this->assertInstanceOf("\Niko\DiceGame\GameRound\GameRound", $gameRound);
    //
    //     $gameRound->rollDices($name);
    //
    //     $gameRound->resetPlayerLastThrowHistory();
    //
    //     $histogram = new Histogram();
    //     $this->assertInstanceOf("\Niko\Histogram\Histogram", $histogram);
    //
    //     $histogram->injectData($gameRound);
    //
    //     $this->assertGreaterThan(0, count($histogram->getSequence()));
    //
    //     $this->assertGreaterThan(0, count($histogram->getAsText()));
    // }
    //
    // /**
    //  *  Tests that the function returns a histogram with values.
    //  */
    // public function testGetAsTextLimited()
    // {
    //     $amountOfPlayers = 2;
    //     $amountOfDices = 2;
    //     $amountOfAI = 1;
    //     $name = "Niko";
    //
    //     $gameRound = new GameRound($amountOfPlayers, $amountOfDices, $amountOfAI, $name);
    //     $this->assertInstanceOf("\Niko\DiceGame\GameRound\GameRound", $gameRound);
    //
    //     $gameRound->rollDices($name);
    //
    //     $gameRound->resetPlayerLastThrowHistory();
    //
    //     $histogram = new Histogram();
    //     $this->assertInstanceOf("\Niko\Histogram\Histogram", $histogram);
    //
    //     $histogram->injectData($gameRound);
    //
    //     $this->assertGreaterThan(0, count($histogram->getSequence()));
    //
    //     $this->assertGreaterThan(0, count($histogram->getAsTextLimited()));
    // }
}
