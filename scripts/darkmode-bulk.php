<?php

$dir = realpath(__DIR__ . '/../resources/views');

function processDir($dir) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            // Exclude folders updated in Phase 1
            if (strpos($path, DIRECTORY_SEPARATOR . 'components') !== false || 
                strpos($path, DIRECTORY_SEPARATOR . 'layouts') !== false || 
                strpos($path, DIRECTORY_SEPARATOR . 'auth') !== false) {
                continue;
            }
            processDir($path);
        } else if (substr($path, -10) === '.blade.php') {
            processFile($path);
        }
    }
}

function processFile($path) {
    $content = file_get_contents($path);
    if (!$content) return;
    
    $map = [
        "bg-white" => "bg-gray-800",
        "bg-gray-50" => "bg-gray-900",
        "bg-gray-100" => "bg-gray-800",
        "text-gray-900" => "text-gray-100",
        "text-gray-800" => "text-gray-200",
        "text-gray-700" => "text-gray-300",
        "text-gray-600" => "text-gray-400",
        "text-gray-500" => "text-gray-400",
        "border-gray-100" => "border-gray-700",
        "border-gray-200" => "border-gray-700",
        "border-gray-300" => "border-gray-600",
    ];
    
    $keys = array_keys($map);
    $pattern = '/(?<![\w\-])(?:([a-z\-]+):)?(' . implode('|', array_map('preg_quote', $keys)) . ')(?![\w\-])/';
    
    $newContent = preg_replace_callback($pattern, function($matches) use ($map) {
        $prefix = $matches[1] ?? '';
        $base = $matches[2];
        
        if ($prefix === 'dark') {
            return $matches[0];
        }
        
        $darkVariant = $map[$base];
        $newClass = $prefix ? "dark:{$prefix}:{$darkVariant}" : "dark:{$darkVariant}";
        
        return $matches[0] . ' ' . $newClass;
    }, $content);
    
    if ($newContent !== $content) {
        // Deduplicate classes inside class="..." and class='...'
        $newContent = preg_replace_callback('/class="([^"]+)"/', function($m) {
            $classes = preg_split('/\s+/', $m[1], -1, PREG_SPLIT_NO_EMPTY);
            $classes = array_unique($classes);
            return 'class="' . implode(' ', $classes) . '"';
        }, $newContent);
        
        $newContent = preg_replace_callback("/class='([^']+)'/", function($m) {
            $classes = preg_split('/\s+/', $m[1], -1, PREG_SPLIT_NO_EMPTY);
            $classes = array_unique($classes);
            return "class='" . implode(' ', $classes) . "'";
        }, $newContent);

        file_put_contents($path, $newContent);
        echo "Updated: $path\n";
    }
}

processDir($dir);

?>
