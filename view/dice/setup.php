<?php
namespace Anax\View;

?>
<h1>Set the game up</h1>

<form class="game-form" method="POST">
    <label for="players">Amount of players</label>
    <input type="number" name="players" min="2" required>

    <label for="dices">Amount of dices</label>
    <input type="number" name="dices" min="1" required>

    <label for="playerNick">Nickname</label>
    <input type="text" name="playerNick" required>

    <input type="submit" value="Play">
</form>
