<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $compiler = app('blade.compiler');
    $path = resource_path('views/components/layouts/app.blade.php');
    $compiled = $compiler->compileString(file_get_contents($path));
    echo "✓ Blade syntax is valid!\n";
} catch (Exception $e) {
    echo "✗ Blade syntax error:\n";
    echo $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
