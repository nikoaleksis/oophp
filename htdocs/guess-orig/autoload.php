<?php
/**
 * Autoloader for classes.
 * @param string $class The name of the class.
 */
spl_autoload_register(function ($class) {
    $path = "src/{$class}.php";
    if (is_file($path)) {
        include($path);
    }
});
