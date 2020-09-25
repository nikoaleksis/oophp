<?php
/**
* Create routes using $app programming style.
*/
//var_dump(array_keys(get_defined_vars()));



/**
* Init the dice game and redirect the player to the game.
*/
$app->router->get("dice/init", function () use ($app) {

    //Destroy previous sessions
    $app->session->destroy();

    // Initiate the session for the game.
    $app->session->start();

    //Redirect to setup view.
    return $app->response->redirect("dice/setup");
});


/**
* Render the view for the setup view
*/
$app->router->get("dice/setup", function () use ($app) {
    $title = "Dice Game Setup";

    $app->page->add("dice/setup");

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
 * Get the post variables from the setup view
*/
$app->router->post("dice/setup", function () use ($app) {
    $players = $app->request->getPost("players");
    $dices = $app->request->getPost("dices");
    $amountOfAI = $players - 1;
    $playerNick = $app->request->getPost("playerNick");

    $app->session->set("gameRound", new Niko\DiceGame\GameRound\GameRound($players, $dices, $amountOfAI, $playerNick));

    return $app->response->redirect("dice/play");
});

/**
* Render the view for the current game.
*/
$app->router->get("dice/play", function () use ($app) {
    $title = "Current Game";
    $gameRound = $app->session->get("gameRound");
    $gameRoundHistory = $gameRound->getGameRoundHistory();
    $players = $gameRound->getPlayerArray();
    $currentPlayerName = $gameRound->currentPlayerName();
    $currentRound = $gameRound->getCurrentRound();
    $currentPlayer = $app->session->get("gameRound")->
    getPlayerByName($currentPlayerName);
    $currentPlayerTurn = $gameRound->getCurrentPlayerTurn();
    $currentPlayer->setIsCurrentPlayer(true);

    $data = [
        "gameRound" => $gameRound,
        "players" => $players,
        "gameRoundHistory" => $gameRoundHistory,
        "currentPlayerName" => $currentPlayerName,
        "currentRound" => $currentRound,
        "currentPlayer" => $currentPlayer,
        "currentPlayerTurn" => $currentPlayerTurn
    ];

    $app->page->add("dice/play", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

$app->router->post("dice/play", function () use ($app) {
    $currentPlayerName = $app->session->get("gameRound")->currentPlayerName();
    $currentPlayer = $app->session->get("gameRound")->
    getPlayerByName($currentPlayerName);
    $gameRound = $app->session->get("gameRound");


    if ($app->request->getPost("throw")) {
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
    } elseif ($app->request->getPost("keep-score")) {
        $gameRound->advancePlayerTurn();
        $currentPlayer->setIsCurrentPlayer(false);
        $currentPlayer->insertIntoThrowHistory($currentPlayer->getLastThrow());
        $gameRound->setGameRoundHistory($currentPlayerName, $currentPlayer->getThrowHistory());
        $gameRound->resetPlayerLastThrowHistory();

        if ($gameRound->getCurrentPlayerTurn() === 0) {
            $gameRound->incrementToNextRound();
        }
    } elseif ($app->request->getPost("simulate")) {
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
        return $app->response->redirect("dice/winner-page");
    }

    return $app->response->redirect("dice/play");
});

$app->router->get("dice/winner-page", function () use ($app) {
    $title = "Final Standings";
    $gameRound = $app->session->get("gameRound");
    $players = $gameRound->getPlayerArray();

    $data = [
        "gameRound" => $gameRound,
        "players" => $players,
    ];

    $app->page->add("dice/winner-page", $data);

    return $app->page->render([
        "title" => $title
    ]);
});
