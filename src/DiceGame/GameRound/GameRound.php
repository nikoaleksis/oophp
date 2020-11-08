<?php
namespace Niko\DiceGame\GameRound;

use \Niko\DiceGame\Player\Player;
use \Niko\Interfaces\HistogramInterface;
use \Niko\Traits\HistogramTrait;

class GameRound implements HistogramInterface
{
    use HistogramTrait;

    /**
     * Variables of the GameRound class.
     * @var int $amountOfPlayers The amount of players in a game round.
     * @var int $amountOfDices The amount of dices in a game round.
     * @var int $amountOfAI The amount of AI (Bots) in a game round.
     * @var array $playerArray The array holding the players.
     * @var array $gameRoundHistory The array holding the history of the game round.
     * @var int $currentPlayerTurn The player turn counter, defaults to 0.
     * @var int $currentRound The round counter, defaults to 0.
     * @var int MAX_SCORE The score when a game will end.
     */
    private $amountOfPlayers;
    private $amountOfDices;
    private $amountOfAI;
    private $playerArray;
    private $gameRoundHistory;
    private $currentPlayerTurn = 0;
    private $currentRound = 0;
    private const MAX_SCORE = 100;

    /**
     * Constructor to initiate a new GameRound.
     * @param int $amountOfPlayers - The amount of players playing the initiated GameRound.
     * @param int $amountOfDices - The amount of dices that will be played.
     * @param int $amountOfAI - The amount of Bots in the GameRound.
     * @param string $playerName - The human player nickname.
     */
    public function __construct(int $amountOfPlayers, int $amountOfDices, int $amountOfAI, string $playerName)
    {
        $this->playerArray = array();

        $this->amountOfPlayers = $amountOfPlayers;
        $this->amountOfDices = $amountOfDices;
        $this->amountOfAI = $amountOfAI;
        $this->gameRoundHistory = array();

        $this->setAmountOfPlayers($this->amountOfPlayers, $this->amountOfDices, $this->amountOfAI, $playerName);
    }

    /**
     * Getter method for fetching a Player from the playerArray.
     * @param string $name The name of the player.
     * @return Player $player Returns a player object if succesful, otherwise null
     */
    public function getPlayerByName(string $name)
    {
        foreach ($this->playerArray as $player) {
            if ($player->getName() === $name) {
                return $player;
            }
        }
        return null;
    }

    /**
    * Method for initiating a player to roll its dices.
    * @param string $playerName The name of the player.
    * @return void
    */
    public function rollDices(string $playerName)
    {
        foreach ($this->playerArray as $player) {
            if ($playerName === $player->getName()) {
                $player->rollDice();
            }
            break;
        }
    }


    /**
     * Getter method for fetching the max score.
     * @return int MAX_SCORE
     */
    public static function getMaxScore()
    {
        return self::MAX_SCORE;
    }

    /**
     * Getter method for fetching the game round history
     * @return array $this->gameRoundHistory The players involved in the round and their actions.
     */
    public function getGameRoundHistory()
    {
        return $this->gameRoundHistory;
    }

    /**
     * Sets the values for a specific index in $this->gameRoundHistory
     * @param string $playerName The index where the values are put. Should be the player name.
     * @param array $throwHistory The throwhistory of the player
     * @return void
     */
    public function setGameRoundHistory(string $playerName, array $throwHistory)
    {
        $this->gameRoundHistory[$playerName] = $throwHistory;
    }

    /**
     * Resets all the players throw history.
     * @return void
     */
    public function resetPlayerLastThrowHistory()
    {
        foreach ($this->playerArray as $player) {
            foreach ($player->getLastThrow() as $value) {
                $this->sequence[] = $value;
            }

            $player->resetLastThrow();
        }
    }

    /**
     * Getter method for getting the amount of players.
     * @return int The amount of players
     */
    public function getAmountOfPlayers()
    {
        return $this->amountOfPlayers;
    }

    /**
     * Getter method for retrieving the amount of dices.
     * @return int The amount of dices.
     */
    public function getAmountOfDices()
    {
        return $this->amountOfDices;
    }

    /**
     * Get the name of the player who's turn it currently is.
     * @return string The name of the player.
     */
    public function currentPlayerName()
    {
        return $this->getPlayerArray()[$this->currentPlayerTurn]->getName();
    }

    /**
     * Get the current player turn (an int ranging from 0 if one player, to however many players are playing).
     * @return int The current player turn, a sort of counter.
     */
    public function getCurrentPlayerTurn()
    {
        return $this->currentPlayerTurn;
    }

    /**
     * Advance the counter to the next player.
     * @return int Returns the next player turn.
     */
    public function advancePlayerTurn()
    {
        $this->currentPlayerTurn++;

        if ($this->currentPlayerTurn >= $this->amountOfPlayers) {
            $this->currentPlayerTurn = 0;

            return $this->currentPlayerTurn;
        }
        return $this->currentPlayerTurn;
    }

    /**
     * Method for returning the correct values for the scoreboard.
     * @param int $index The index for the second array, should point to a specific round.
     * @param array $firstArray This is supposed to be the specific players last throw.
     * @param array $secondArray This is supposed to be the the complete throw history of the player.
     * @return array The values that are supposed to be shown in the scoreboard.
     */
    public function getThrowsForScoreboard(int $index, array $firstArray, array $secondArray)
    {
        if (!empty($firstArray)) {
            return array_values($firstArray);
        } elseif (array_key_exists($index, $secondArray)) {
            return array_values($secondArray[$index]);
        }

        return array_values([0 => "Not Thrown Yet"]);
    }

    /**
     * Getter for retrieving the game rounds player array.
     * @return array  $this->playerArray The player array.
     */
    public function getPlayerArray()
    {
        return $this->playerArray;
    }

    /**
     * Get the current round.
     * @return int $this->currentRound The current round.
     */
    public function getCurrentRound()
    {
        return $this->currentRound;
    }

    /**
     * Increments the round counter to the next round.
     * @return void
     */
    public function incrementToNextRound()
    {
        $this->currentRound++;
    }

    /**
     * Get the highest score a player currently has.
     * @return int  The highest score a player currently holds.
     */
    public function getHighestScore()
    {
        $scores = array();

        foreach ($this->playerArray as $player) {
            $scores[] = $player->getScore();
        }

        return max($scores);
    }

    /**
     * Simulates a bot throwing dices.
     * @param string $playerName The name of the bot.
     * @return string Message if prayer throws a 1.
     * @return void
     */
    public function simulateAI(string $playerName)
    {
        $highestScore;
        $keepPlaying = true;
        $timesThrown = 0;

        $highestScore = $this->getHighestScore();

        foreach ($this->playerArray as $player) {
            if ($player->getName() === $playerName) {
                while ($keepPlaying) {
                    $player->rollDice();

                    if ($player->getScore() >= ($highestScore * 1.25) || $player->getScore() >= self::MAX_SCORE) {
                        $keepPlaying = false;
                    } elseif ($player->getScore() >= ($highestScore * 0.95) || $player->getScore() >= self::MAX_SCORE) {
                        $keepPlaying = false;
                    }

                    if (in_array(1, $player->getLastThrow())) {
                        $player->setScore(($player->getScore() - array_sum($player->getLastThrow())));

                        return "AI threw a 1";
                    }
                    $timesThrown++;
                }
                break;
            }
        }
    }

    /**
     * Checks for the winner and sorts the player array so the winner is shown at the 1st index.
     * @return boolean $playerWon A boolean flag, showing if a player has reached the MAX_SCORE.
     */
    public function checkForWinner()
    {
        $playerWon = false;

        foreach ($this->playerArray as $player) {
            if ($player->getScore() >= 100) {
                $playerWon = true;
                break;
            }
        }

        if ($playerWon) {
            usort($this->playerArray, array(Player::class, "comparator"));
        }

        return $playerWon;
    }

    /**
     * Private function that sets the amount of players in the player array.
     * Instantiates the players and adds them to the player array.
     * Is supposed to be used in the constructor.
     * @return void
     */
    private function setAmountOfPlayers(int $amountOfPlayers, int $amountOfDices, int $amountOfAI, string $playerName)
    {
        $this->playerArray[] = new Player(false, $amountOfDices, $playerName);

        for ($i = 0; $i < $amountOfPlayers; $i++) {
            if ($amountOfAI > 0) {
                for ($j = 0; $j < $amountOfAI; $j++) {
                    $this->playerArray[] = new Player(true, $amountOfDices);

                    $i++;
                }
            }
        }
    }
}
