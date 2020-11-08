<?php

namespace Niko\Controller;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;
use Niko\DiceGame\GameRound\GameRound;
use Niko\Histogram\Histogram;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A controller for rendering content and playing the dice game.
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class DiceGameController implements AppInjectableInterface
{
    use AppInjectableTrait;

    /**
     * Initialize the game.
     * @return object ResponseUtility
     */
    public function initActionGet() : object
    {
        //Destroy previous sessions
        $this->app->session->destroy();

        // Initiate the session for the game.
        $this->app->session->start();

        //Redirect to setup view.
        return $this->app->response->redirect("dice/setup");
    }

    /**
     * Sets up the game.
     * @return object ResponseUtility
     */
    public function setupActionGet() : object
    {
        $title = "Dice Game Setup";

        $this->app->page->add("dice/setup");

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
     * Posts the game settings from the setup.
     * @return object ResponseUtility
     */
    public function setupActionPost() : object
    {
        $players = $this->app->request->getPost("players");
        $dices = $this->app->request->getPost("dices");
        $amountOfAI = $players - 1;
        $playerNick = $this->app->request->getPost("playerNick");

        $this->app->session->set("gameRound", new GameRound($players, $dices, $amountOfAI, $playerNick));
        $this->app->session->set("histogram", new Histogram());

        return $this->app->response->redirect("dice/play");
    }

    /**
     * Renders the page where the game is played and provides the correct values.
     * @return object ResponseUtility
     */
    public function playActionGet() : object
    {
        $title = "Current Game";
        $gameRound = $this->app->session->get("gameRound");
        $gameRoundHistory = $gameRound->getGameRoundHistory();
        $players = $gameRound->getPlayerArray();
        $currentPlayerName = $gameRound->currentPlayerName();
        $currentRound = $gameRound->getCurrentRound();
        $currentPlayer = $this->app->session->get("gameRound")->
        getPlayerByName($currentPlayerName);
        $currentPlayerTurn = $gameRound->getCurrentPlayerTurn();
        $currentPlayer->setIsCurrentPlayer(true);
        $histogram = $this->app->session->get("histogram");

        $data = [
            "gameRound" => $gameRound,
            "players" => $players,
            "gameRoundHistory" => $gameRoundHistory,
            "currentPlayerName" => $currentPlayerName,
            "currentRound" => $currentRound,
            "currentPlayer" => $currentPlayer,
            "currentPlayerTurn" => $currentPlayerTurn,
            "histogram" => $histogram
        ];

        $this->app->page->add("dice/play", $data);

        return $this->app->page->render([
            "title" => $title,
        ]);
    }

    /**
     * Handles the post values when the game is being played.
     * @return object ResponseUtility
     */
    public function playActionPost() : object
    {
        $currentPlayerName = $this->app->session->get("gameRound")->currentPlayerName();
        $currentPlayer = $this->app->session->get("gameRound")->
        getPlayerByName($currentPlayerName);
        $gameRound = $this->app->session->get("gameRound");
        $histogram = $this->app->session->get("histogram");

        if ($this->app->request->getPost("throw")) {
            $gameRound->rollDices($currentPlayerName);

            if (in_array(1, $currentPlayer->getLastThrow())) {
                $currentPlayer->setScore(
                    ($currentPlayer->getScore() - array_sum($currentPlayer->getLastThrow()))
                );
                $gameRound->advancePlayerTurn();

                if ($gameRound->getCurrentPlayerTurn() === 0) {
                    $gameRound->incrementToNextRound();
                }

                $currentPlayer->setIsCurrentPlayer(false);
                $currentPlayer->insertIntoThrowHistory($currentPlayer->getLastThrow());
                $gameRound->setGameRoundHistory($currentPlayerName, $currentPlayer->getThrowHistory());
                $gameRound->resetPlayerLastThrowHistory();
            }
        } elseif ($this->app->request->getPost("keep-score")) {
            $gameRound->advancePlayerTurn();
            $currentPlayer->setIsCurrentPlayer(false);
            $currentPlayer->insertIntoThrowHistory($currentPlayer->getLastThrow());
            $gameRound->setGameRoundHistory($currentPlayerName, $currentPlayer->getThrowHistory());
            $gameRound->resetPlayerLastThrowHistory();

            if ($gameRound->getCurrentPlayerTurn() === 0) {
                $gameRound->incrementToNextRound();
            }
        } elseif ($this->app->request->getPost("simulate")) {
            $gameRound->simulateAI($currentPlayerName);
            $gameRound->advancePlayerTurn();

            if ($gameRound->getCurrentPlayerTurn() === 0) {
                $gameRound->incrementToNextRound();
            }

            $currentPlayer->setIsCurrentPlayer(false);
            $currentPlayer->insertIntoThrowHistory($currentPlayer->getLastThrow());
            $gameRound->setGameRoundHistory($currentPlayerName, $currentPlayer->getThrowHistory());
            $gameRound->resetPlayerLastThrowHistory();
        }
        if ($gameRound->checkForWinner()) {
            return $this->app->response->redirect("dice/winner");
        }

        $histogram->injectData($gameRound);

        return $this->app->response->redirect("dice/play");
    }

    /**
     * Renders the winner page.
     * @return object
     */
    public function winnerActionGet() : object
    {
        $title = "Final Standings";
        $gameRound = $this->app->session->get("gameRound");
        $players = $gameRound->getPlayerArray();

        $data = [
            "gameRound" => $gameRound,
            "players" => $players,
        ];

        $this->app->page->add("dice/winner", $data);

        return $this->app->page->render([
            "title" => $title
        ]);
    }
}
