<h1>Guess My Number</h1>

<p>Guess a number between 1 and 100, you have <?= $_SESSION["game"]->tries() ?> left.</p>

<form class="number-guess" action="index.php" method="post">
    <input type="number" name="guess">
    <input type="<?= $_SESSION["game"]->tries() > 0 ? "submit" : "hidden" ?>" name=<?= $_SESSION["game"]->tries() != 0 ? "doGuess" : "" ?> value="Make a guess">
    <input type="submit" name="doInit" value="Start from beginning">
    <input type="submit" name="doCheat" value="Cheat">
</form>

<p><?= $res ?></p>
