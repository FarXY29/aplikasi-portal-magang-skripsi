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
    __DIR__ . '/../resources/views/auth/login.blade.php',
    __DIR__ . '/../resources/views/auth/register.blade.php',
    __DIR__ . '/../resources/views/auth/forgot-password.blade.php',
    __DIR__ . '/../resources/views/auth/reset-password.blade.php',
    __DIR__ . '/../resources/views/auth/verify-email.blade.php',
    __DIR__ . '/../resources/views/auth/confirm-password.blade.php',
];

$replacements = [
    "bg-white rounded-3xl" => "bg-white dark:bg-gray-800 rounded-3xl",
    "border border-gray-100" => "border border-gray-100 dark:border-gray-700",
    "text-gray-900" => "text-gray-900 dark:text-gray-100",
    "text-gray-700" => "text-gray-700 dark:text-gray-300",
    "text-gray-600 group-hover:text-gray-900" => "text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-100",
    "text-gray-500" => "text-gray-500 dark:text-gray-400",
    "bg-gray-50 focus:bg-white" => "bg-gray-50 dark:bg-gray-900 focus:bg-white dark:focus:bg-gray-800",
    "bg-white text-gray-400" => "bg-white dark:bg-gray-800 text-gray-400 dark:text-gray-500",
    "border-gray-200" => "border-gray-200 dark:border-gray-700",
    "border-gray-300" => "border-gray-300 dark:border-gray-700",
];

foreach ($files as $file) {
    if (file_exists($file)) {
        replaceInFile($file, $replacements);
    }
}
