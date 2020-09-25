<?php

namespace Niko\DiceGame\Dice;

use PHPUnit\Framework\TestCase;

class DiceCreateObjectTest extends TestCase
{
    /**
     *  Create object and test that the object has the expected values.
     */
    public function testCreateObject()
    {
        $dice = new Dice();
        $this->assertInstanceOf("\Niko\DiceGame\Dice\Dice", $dice);

        $this->assertGreaterThanOrEqual(1, $dice->getValue());
        $this->assertLessThanOrEqual(6, $dice->getValue());
    }
}
