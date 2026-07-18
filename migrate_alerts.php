<?php

$dir = __DIR__ . '/resources/views';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$bladeFiles = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

$successPattern = '/@if\(\s*session\(\'success\'\)\s*\)\s*<div\s+x-data="\{\s*show:\s*true\s*\}"[^>]*>.*?{{ session\(\'success\'\) }}.*?<\/div>\s*@endif/s';
$successReplacement = "@if(session('success'))\n    <x-ui.alert type=\"success\" class=\"mb-4\">\n        {{ session('success') }}\n    </x-ui.alert>\n@endif";

$errorPattern = '/@if\(\s*session\(\'error\'\)\s*\)\s*<div\s+x-data="\{\s*show:\s*true\s*\}"[^>]*>.*?{{ session\(\'error\'\) }}.*?<\/div>\s*@endif/s';
$errorReplacement = "@if(session('error'))\n    <x-ui.alert type=\"error\" class=\"mb-4\">\n        {{ session('error') }}\n    </x-ui.alert>\n@endif";

$count = 0;

foreach ($bladeFiles as $file) {
    $path = $file[0];
    // Jangan ubah komponen alert itu sendiri
    if (strpos($path, 'alert.blade.php') !== false) continue;
    
    $content = file_get_contents($path);
    $original = $content;
    
    $content = preg_replace($successPattern, $successReplacement, $content);
    $content = preg_replace($errorPattern, $errorReplacement, $content);
    
    if ($content !== $original) {
        file_put_contents($path, $content);
        echo "Updated: " . str_replace(__DIR__ . '/', '', $path) . "\n";
        $count++;
    }
}

echo "Total files updated: $count\n";
