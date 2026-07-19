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

$replacementsTextInput = [
    "bg-gray-50 border border-gray-200" => "bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700",
    "focus:bg-white" => "focus:bg-white dark:focus:bg-gray-800",
    "text-gray-800 placeholder-gray-400" => "text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500",
];
replaceInFile(__DIR__ . '/../resources/views/components/text-input.blade.php', $replacementsTextInput);

$replacementsSecBtn = [
    "bg-white border border-gray-300" => "bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600",
    "text-gray-700" => "text-gray-700 dark:text-gray-300",
    "hover:bg-gray-50" => "hover:bg-gray-50 dark:hover:bg-gray-700",
];
replaceInFile(__DIR__ . '/../resources/views/components/secondary-button.blade.php', $replacementsSecBtn);

$replacementsModal = [
    "bg-white rounded-2xl overflow-hidden shadow-2xl" => "bg-white dark:bg-gray-900 rounded-2xl overflow-hidden shadow-2xl border dark:border-gray-700",
];
if (file_exists(__DIR__ . '/../resources/views/components/modal.blade.php')) {
    replaceInFile(__DIR__ . '/../resources/views/components/modal.blade.php', $replacementsModal);
}
