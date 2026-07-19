<?php
$filePath = __DIR__ . '/resources/views/admin_kota/dashboard.blade.php';
$lines = file($filePath);
if ($lines === false) die("Failed to read file.");

$dir = __DIR__ . '/resources/views/admin_kota/dashboard';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

function replaceLinesWithInclude(&$lines, $start, $end, $includeName, $partialPath) {
    $startIdx = $start - 1;
    $endIdx = $end - 1;
    
    $content = array_slice($lines, $startIdx, $endIdx - $startIdx + 1);
    file_put_contents($partialPath, implode("", $content));
    
    array_splice($lines, $startIdx, $endIdx - $startIdx + 1, ["        @include('admin_kota.dashboard.$includeName')\n"]);
}

// Ensure extracting in reverse order so line numbers remain valid for earlier extractions
replaceLinesWithInclude($lines, 441, 495, '_recent-activity', "$dir/_recent-activity.blade.php");
replaceLinesWithInclude($lines, 287, 328, '_charts', "$dir/_charts.blade.php");
replaceLinesWithInclude($lines, 185, 285, '_stats-grid', "$dir/_stats-grid.blade.php");

file_put_contents($filePath, implode("", $lines));
echo "Successfully extracted admin_kota partials!\n";

