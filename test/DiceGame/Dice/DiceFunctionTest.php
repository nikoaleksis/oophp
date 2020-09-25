<?php

namespace Niko\DiceGame\Dice;

use PHPUnit\Framework\TestCase;

class DiceFunctionTest extends TestCase
{
    /**
     *  Test the roll function of the dice class.
     */
    public function testRollFunction()
    {
        $dice = new Dice();
        $this->assertInstanceOf("\Niko\DiceGame\Dice\Dice", $dice);

        $firstValue = $dice->getValue();

        $this->assertGreaterThanOrEqual(1, $firstValue);
        $this->assertLessThanOrEqual(6, $firstValue);

        $dice->roll();

        $secondValue = $dice->getValue();

        $this->assertGreaterThanOrEqual(1, $secondValue);
        $this->assertLessThanOrEqual(6, $secondValue);

        //Will fail sometimes
        $this->assertNotSame($firstValue, $secondValue);
    }
}
