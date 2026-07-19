<?php

function replaceInFile($file, $replacements) {
    $content = file_get_contents($file);
    if (!$content) return;
    
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    
    file_put_contents($file, $content);
    echo "Updated: $file\n";
}

$files = [
    __DIR__ . '/../resources/views/layouts/navigation.blade.php',
];

$replacements = [
    "'text-gray-600 hover:bg-teal-50/80 hover:text-teal-700'" => "'text-gray-600 dark:text-gray-300 hover:bg-teal-50/80 dark:hover:bg-gray-700 hover:text-teal-700 dark:hover:text-white'",
    "text-gray-400 group-hover:text-teal-600" => "text-gray-400 dark:text-gray-500 group-hover:text-teal-600 dark:group-hover:text-white",
    "border-t border-gray-100" => "border-t border-gray-100 dark:border-gray-700/50",
    "bg-gradient-to-r from-teal-700 via-teal-600 to-teal-700" => "bg-gradient-to-r from-teal-700 via-teal-600 to-teal-700 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900",
    "border-b border-teal-800/20" => "border-b border-teal-800/20 dark:border-gray-700/80",
];

foreach ($files as $file) {
    if (file_exists($file)) {
        replaceInFile($file, $replacements);
    }
}
