<?php

namespace Anax\View;

/**
 * Render content within an article.
 */

// Show incoming variables and view helper functions
//echo showEnvironment(get_defined_vars(), get_defined_functions());

?><hr>
<pre>
SESSION
<?= var_dump($_SESSION) ?>
POST
<?= var_dump($_POST) ?>
GET
<?= var_dump($_SESSION) ?>
TRIES
<?= var_dump($_SESSION["game"]->tries()) ?>
SECRET NUMBER
<?= var_dump($_SESSION["game"]->number()) ?>
CHEAT ACTIVE
<?= var_dump($doCheat) ?>
</pre>
