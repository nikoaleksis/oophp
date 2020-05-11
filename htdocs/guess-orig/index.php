<?php
require __DIR__ . "/autoload.php";
require __DIR__ . "/config.php";

$number = $_POST["guess"] ?? null;
$doInit = $_POST["doInit"] ?? null;
$doGuess = $_POST["doGuess"] ?? null;
$doCheat = $_POST["doCheat"] ?? null;

$res = "";

if (!isset($_SESSION["game"])) {
    $_SESSION["game"] = new Guess();
}



if (isset($doGuess)) {
    $res = "<p>{$_SESSION["game"]->makeGuess($number)}</p>";
} elseif (isset($doCheat)) {
    echo "<p>The secret number is: ";
    if ($_SESSION["game"]->number() !== null) {
        echo "{$_SESSION["game"]->number()}</p>";
    } else {
        echo "You haven't started the game yet.</p>";
    }
} elseif (isset($doInit)) {
    $_SESSION["game"]->random();
    header("Location: index.php");
}

require __DIR__ . "/view/guess_my_number.php";
//var_dump($doCheat);
//var_dump($doInit);
//var_dump($doGuess);
//var_dump($number);

//var_dump($_SESSION["game"]);
