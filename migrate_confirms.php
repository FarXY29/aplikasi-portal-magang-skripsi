<?php

$dir = __DIR__ . '/resources/views';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$bladeFiles = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$pattern1 = '/onsubmit="return\s*confirm\(\'([^\']+)\'\)\;?"/s';
$pattern2 = '/onsubmit="return\s*confirm\(\'([^\']+)\'\)\;?"/s'; // Same, but just to be sure we catch ones with or without semicolon.

$count = 0;

foreach ($bladeFiles as $file) {
    $path = $file[0];
    
    // Skip confirm-dialog itself
    if (strpos($path, 'confirm-dialog.blade.php') !== false) continue;
    
    $content = file_get_contents($path);
    $original = $content;
    
    $content = preg_replace_callback($pattern1, function($matches) {
        $msg = $matches[1];
        // Clean up msg slightly if needed, but keeping it as is since it was inside JS string
        return '@submit.prevent="$dispatch(\'open-confirm\', { message: \'' . $msg . '\', onConfirm: () => $el.submit() })"';
    }, $content);
    
    if ($content !== $original) {
        file_put_contents($path, $content);
        echo "Updated: " . str_replace(__DIR__ . '/', '', $path) . "\n";
        $count++;
    }
}

echo "Total files updated for confirm: $count\n";
