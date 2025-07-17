#!/usr/bin/env php
<?php

/**
 * Citus Benchmark Runner
 * Simple CLI script to run various benchmark operations
 */

$commands = [
    'install' => 'composer install',
    'test' => 'php src/php/benchmark.php --test',
    'benchmark' => 'php src/php/benchmark.php',
    'batch' => 'php src/php/benchmark.php --batch',
    'cleanup' => 'php src/php/benchmark.php --cleanup',
    'docker-up' => 'docker-compose up -d',
    'docker-down' => 'docker-compose down'
];

function showHelp() {
    global $commands;
    
    echo "Citus PHP Benchmark Runner\n";
    echo "==========================\n\n";
    echo "Available commands:\n";
    
    foreach ($commands as $cmd => $actual) {
        echo "  " . str_pad($cmd, 12) . " - {$actual}\n";
    }
    
    echo "\nUsage: php run.php <command>\n";
    echo "Example: php run.php test\n\n";
}

if ($argc < 2) {
    showHelp();
    exit(1);
}

$command = $argv[1];

if (!isset($commands[$command])) {
    echo "Unknown command: {$command}\n\n";
    showHelp();
    exit(1);
}

$actualCommand = $commands[$command];
echo "Running: {$actualCommand}\n";
echo str_repeat('-', 50) . "\n";

// Execute the command
passthru($actualCommand, $returnCode);

exit($returnCode);
