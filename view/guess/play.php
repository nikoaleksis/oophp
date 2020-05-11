<?php

namespace Anax\View;

/**
 * Render game content
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

?>
<h1>Guess My Number</h1>

<p>Guess a number between 1 and 100, you have <?= $_SESSION["game"]->tries() ?> left.</p>

<form class="number-guess" method="post">
    <input type="number" name="guess">
    <input type="<?= $_SESSION["game"]->tries() > 0 ? "submit" : "hidden" ?>" name=<?= $_SESSION["game"]->tries() != 0 ? "doGuess" : "" ?> value="Make a guess">
    <input type="submit" name="doInit" value="Start from beginning">
    <input type="submit" name="doCheat" value="Cheat">
</form>

<?php if ($doCheat) : ?>
    <p><strong>CHEAT</strong> Current number is: <?= $_SESSION["game"]->number() ?></p>
<?php endif; ?>

<p><?= $res ?></p>
