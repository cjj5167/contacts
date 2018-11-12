<?php

if (-1 === version_compare(phpversion(), '7.1')) {
    echo "This project requires at least PHP 7.1. Please upgrade to continue...\r\n";
    exit(1);
} else {
    echo sprintf("Running PHP %s. GREAT!\r\n", phpversion());
}

if (! function_exists('sodium_add')) {
    echo "This project requires Libsodium support. Please install it to continue...\r\n";
    exit(1);
} else {
    echo "Libsodium is available. Great!\r\n";
}

if ( ! class_exists('SQLite3')) {
    echo "This project requires SQLite support. Please install it to continue...\r\n";
    exit(1);
} else {
    echo "SQLite is available. Great!\r\n";
}

try {
    new \PDO('sqlite::memory:');
    echo "PDO drivers for SQLite are available. Great!\r\n";
} catch (\Exception $exception) {
    echo "This project requires PDO driver support for SQLite. Please install it to continue...\r\n";
    exit(1);
}