<?php

/**
 * PHPUnit bootstrap file.
 *
 * Set include path and initialize autoloader.
 */

$base = __DIR__ . '/..';

// Include libraries
require_once $base . '/decomposer.autoload.inc.php';

// Define application config lookup path
$paths = [
    get_include_path(),
    $base . '/config',
    $base . '/src',
];

set_include_path(
    implode(':', $paths)
);

if (defined('TEST_STATICS') === FALSE)
{
    define('TEST_STATICS', __DIR__ . '/statics');
}

?>
