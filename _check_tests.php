<?php
// Run tests and capture output
$output = [];
$exitCode = 0;
exec('php artisan test --compact 2>&1', $output, $exitCode);
echo "Exit code: $exitCode\n";
echo implode("\n", array_slice($output, -20));
