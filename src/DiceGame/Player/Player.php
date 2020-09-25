<?php
namespace Niko\DiceGame\Player;

use \Niko\DiceGame\Dice\Dice;

class Player
{

    /**
     * Variables of the Player class.
     * @var int $name The name of the player.
     * @var boolean $isAi Tells if a player is a bot.
     * @var int $score The player score.
     * @var int $amountOfDices The amount of dices the player has.
     * @var array $diceArray The array that holds the Dice objects.
     * @var array $lastThrow The array that holds the scores from the latest throw.
     * @var array $throwHistory The array that holds all the scores.
     * @var boolean $isCurrentPlayer Tells if it currently is the players turn to act.
     */
    private $name;
    private $isAI;
    private $score;
    private $amountOfDices;
    private $diceArray;
    private $lastThrow;
    private $throwHistory;
    private $isCurrentPlayer;

    /**
     * Constructor to initiate a new Player.
     * @param bool $isAI - Tells if the player created is a bot.
     * @param int $amountOfDices - The amount of dices that will be played.
     * @param string $playerName - The player nick name,
     * creates a random name starting with 'AI' if the player is a bot.
     */
    public function __construct(bool $isAI, int $amountOfDices, string $name = "AI-")
    {

        $this->name = $name;

        if ($isAI == true) {
            $this->name = $name . rand(1, 10000);
        }

        $this->score = 0;
        $this->diceArray = array();

        $this->isAI = $isAI;
        $this->amountOfDices = $amountOfDices;
        $this->throwHistory = array();
        $this->lastThrow = array();

        $this->setAmountOfDices($this->amountOfDices);
    }

    /**
     * Rolls all the dices that a player holds.
     * @return void.
     */
    public function rollDice()
    {
        foreach ($this->diceArray as $dice) {
            $dice->roll();

            $this->score = $this->score + $dice->getValue();
            $this->lastThrow[] = $dice->getValue();
        }
    }

    /**
     * Get the lastThrow array.
     * @return array $this->lastThrow The last throw array.
     */
    public function getLastThrow()
    {
        return $this->lastThrow;
    }

    /**
     * Resets the last throw array.
     * @return void.
     */
    public function resetLastThrow()
    {
        $this->lastThrow = array();
    }

    /**
     * Get the boolean value of if the player is next to act.
     * @return boolean $this->currentPlayer Returns the boolean value.
     */
    public function isCurrentPlayer()
    {
        return $this->isCurrentPlayer;
    }

    /**
     * Sets the value of if a player is next to act.
     * @param $isCurrentPlayer Sets the boolean value of if a player is next to act.
     * @return void
     */
    public function setIsCurrentPlayer(bool $isCurrentPlayer)
    {
         $this->isCurrentPlayer = $isCurrentPlayer;
    }

    /**
     * Inserts an array into throwHistory.
     * @param array $lastRound - The array containing the throws from the last round.
     * @return void.
     */
    public function insertIntoThrowHistory(array $lastRound)
    {
        $this->throwHistory[] = $lastRound;
    }

    /**
     * Get the throw history array.
     * @return array $this->throwHistory The throw history of the player.
     */
    public function getThrowHistory()
    {
        return $this->throwHistory;
    }

    /**
     * Get the player name.
     * @return $this->name The name of the player
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Checks if the player is a bot.
     * @return boolean $this->isAI true or false depending if the player is a bot.
     */
    public function isAI()
    {
        return $this->isAI;
    }

    /**
     * Get the player score.
     * @return $this->score Returns the player score.
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Sets the score of a player.
     * @param int $score The score
     * @return void
     */
    public function setScore(int $score)
    {
        $this->score = $score;
    }

    /**
     * Get the array of dices.
     * $return array $this->diceArray The array of dices.
     */
    public function getDiceArray()
    {
        return $this->diceArray;
    }

    /**
     * Get the amount of dices.
     * @return int $this->amountOfDices Returns the amount of dices.
     */
    public function getAmountOfDices()
    {
        return $this->amountOfDices;
    }

    /**
     * Comparator function, compares the players based on their $scores
     * @return int Returns -1 if lower, 0 if same, 1 if higher.
     */
    public static function comparator($player1, $player2)
    {
        if ($player1->getScore() === $player2->getScore()) {
            return 0;
        }

        return ($player1->getScore() < $player2->getScore()) ? 1 : -1;
    }

    /**
     * Private function that sets up the dices that a player holds, is used in the constructor.
     * @param int $amountOFDices The amount of dices.
     * @return void
     */
    private function setAmountOfDices(int $amountOfDices)
    {
        for ($i = 0; $i < $amountOfDices; $i++) {
            $this->diceArray[] = new Dice();
        }
    }
}
