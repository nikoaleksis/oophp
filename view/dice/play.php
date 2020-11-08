<?php
namespace Anax\View;

//var_dump($gameRound);
?>
<div class="dice-game">
    <h1>Spelregler</h1>
    <p>En spelrunda inleds av en spelare genom att den kastar alla tärningar.
        Alla tärningar med ögon 2-6 summeras och adderas till totalen för nuvarande spelrunda.
        En tvåa är värd 2 poäng och en sexa är värd 6 poäng, och så vidare.
        Spelaren bestämmer om ett nytt kast skall göras inom samma spelrunda för att försöka samla mer poäng.
        Eller så väljer spelaren att avbryta spelrundan och föra över de insamlade poängen till säkerhet i protokollet.
        Om spelaren kastar en etta så avbryts spelrundan och turen går över till nästa spelare.
        Nuvarande spelare förlorar alla poäng som samlats in i nuvaranade spelrunda.</p>
        <p>Den som får 100 poäng först vinner!</p>

        <h2>Protokoll</h2>
    <table class="game-scoreboard">
        <tr>
            <td class="empty"><strong>Nickname: </strong></td>
            <?php
            foreach ($players as $player) {
                if ($player->getName() === $currentPlayerName) {
                    echo "<td class='selected'>";
                } else {
                    echo "<td><b>";
                }
                echo $player->getName() . "</b></td>";
            }
            ?>
        </tr>

        <?php
        $roundHistoryIndex = 0;
        if (count($gameRoundHistory) !== 0) {
            for ($i = 0; $i < $currentRound; $i++) {
                echo "<tr><td><b>Round " . ($i + 1) . ": </b></td>";

                foreach ($players as $player) {
                    echo "<td>" .
                    implode(
                        ", ",
                        array_values(
                            (isset($player->getThrowHistory()[$roundHistoryIndex])
                            ? $player->getThrowHistory()[$roundHistoryIndex] : [])
                        )
                    )
                    . "</td>";
                }
                echo "</tr>";
                $roundHistoryIndex++;
            }
        }
        echo "<tr><td><b>Current Round:</b></td>";

        foreach ($players as $player) {
            echo "<td>" .
            implode(", ", $gameRound->getThrowsForScoreboard(
                $currentRound,
                $player->getLastThrow(),
                $player->getThrowHistory()
            ))
            . "</td>";
        }
        echo "</tr>";
        echo "<tr><td><b>Total Score: </b></td>";

        foreach ($players as $player) {
            echo "<td>" . $player->getScore() . "</td>";
        }
        echo "</tr>";
        ?>
    </table>

    <form class="send-score" action="play" method="post">
        <input type="submit"
            name="<?= $currentPlayer->isAI() ? "simulate" : "throw" ?>"
            value="<?= $currentPlayer->isAI() ? "Simulate Round" : "Throw Dice" ?>">
        <?php
        if (!empty($currentPlayer->getLastThrow())) {
            echo "<input type='submit' name='keep-score' value='Keep Score'>";
        }
        ?>
    </form>
    <div class="histogram">
        <h3>Histogram</h3>
        <p>Shows after the first round.</p>
        <pre><?= $histogram->getAsTextLimited() ?></pre>
    </div>
</div>
