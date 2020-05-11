<?php
/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));



/**
 * Init the guessing game and redirect the player to the game.
 */
$app->router->get("guess/init", function () use ($app) {
    // Init the session for starting the game;

    $_SESSION["game"] = new \Niko\Guess\Guess();


    return $app->response->redirect("guess/play");
});



/**
 * Game status.
 */
$app->router->get("guess/play", function () use ($app) {
    $title = "Guessing Game";

    if ($_SESSION["game"]->tries() === 6) {
        $_SESSION["res"] = null;
    }
    $res = $_SESSION["res"] ?? null;

    $doCheat = $_SESSION["cheat-active"] ?? null;


    $data = [
        "tries" => $_SESSION["game"]->tries() ?? null,
        "doGuess" => $doGuess ?? null,
        "doCheat" => $doCheat ?? null,
        "res" => $res ?? null,
    ];

    $app->page->add("guess/play", $data);
//    $app->page->add("guess/debug", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});

/**
  * Post route for playing the game
 */
$app->router->post("guess/play", function () use ($app) {

    $guess = $_POST["guess"] ?? null;
    $doInit = $_POST["doInit"] ?? null;
    $doGuess = $_POST["doGuess"] ?? null;
    $doCheat = $_POST["doCheat"] ?? null;

    $_SESSION["cheat-active"] = false;

    $res = null;

    if (isset($doGuess)) {
        $_SESSION["res"] = $_SESSION["game"]->makeGuess($guess);
    } elseif (isset($doCheat)) {
        $_SESSION["cheat-active"] = true;
    } elseif (isset($doInit)) {
        $_SESSION["game"]->random();
    }

    return $app->response->redirect("guess/play");
});
