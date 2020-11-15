<?php
namespace Anax\View;

?>

<div class="winner-page">
    <h1>Here are the final standings!<h1>
    <table>
    <?php
    $i = 1;
    foreach ($players as $player) {
        echo "<tr><td>";
        echo  $i . " Place: ";
        echo "</td><td>";
        echo $player->getName();
        echo "</td><td> with ";
        echo $player->getScore() . " Points";
        echo "</td><tr>";
        $i++;
    }
    ?>
    </table>
</div>
