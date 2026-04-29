<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $setting = App\Models\Setting::first() ?? new App\Models\Setting();
    $html = view('setting.index', compact('setting'))->render();
    echo 'Setting view OK, len=' . strlen($html) . PHP_EOL;
} catch (Throwable $e) {
    echo 'ERR: ' . $e->getMessage() . PHP_EOL;
    echo 'File: ' . $e->getFile() . ':' . $e->getLine() . PHP_EOL;
    // Cek compiled view
    if (str_contains($e->getFile(), 'cache')) {
        $lines = file($e->getFile());
        $start = max(0, $e->getLine() - 3);
        $end   = min(count($lines), $e->getLine() + 3);
        for ($i = $start; $i < $end; $i++) {
            echo ($i+1) . ': ' . $lines[$i];
        }
    }
}
