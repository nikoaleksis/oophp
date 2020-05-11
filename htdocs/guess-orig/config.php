<?php
/**
 * Set error reporting.
 */
error_reporting(-1); //Report all types of errors
ini_set("display_errors", 1); // Display the eroors

// Start the named session,
// the name is based on the path to this file.
$name = "NUMBERGAME";
session_name($name);
session_start();


/**
 *
 */
set_exception_handler(function ($e) {
    echo "<p>Anax: Uncaught exception: </p><p>Line "
        . $e->getLine()
        . " in file: "
        . $e->getFile()
        . "</p><p><code>"
        . "</code></p><p>"
        . $e->getMessage()
        . "</p><p>Code: "
        . $e->getCode()
        . "</p><pre>"
        . $e->getTraceAsString()
        . "</pre>";
});
// Unset all of the session variables.
//$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
// if (ini_get("session.use_cookies")) {
//     $params = session_get_cookie_params();
//     setcookie(session_name(), '', time() - 42000,
//     $params["path"], $params["domain"],
//     $params["secure"], $params["httponly"]
// );
// }

// Finally, destroy the session.
 // session_destroy();
