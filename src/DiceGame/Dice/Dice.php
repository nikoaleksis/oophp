<?php
namespace Niko\DiceGame\Dice;

class Dice
{

    /**
     * @var int $value The current value of the dice;
    */

    private $value;

    /**
    * No arg constructor to initialize the value of the dice.
    */
    public function __construct()
    {
        $this->value = rand(1, 6);
    }

    /**
    * Roll the dice to set a value.
    *
    * @return void
    */
    public function roll()
    {
        $this->value = rand(1, 6);
    }

    /**
     * Get the current value of the dice;
     * @return int $this->value The value of the dice.
     */
    public function getValue()
    {
        return $this->value;
    }
}
