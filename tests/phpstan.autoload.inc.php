<?php

/**
 * PHPStan bootstrap file.
 *
 * Set include path and initialize autoloader.
 */

$base = __DIR__ . '/..';

// Load decomposer autoloader.
require_once $base . '/decomposer.autoload.inc.php';

// Define application config lookup path
$paths = [
    get_include_path(),
    $base . '/src',
];

set_include_path(
    implode(':', $paths)
);

?>
