<?php

$filePath = __DIR__ . '/resources/views/peserta/dashboard.blade.php';
$lines = file($filePath);
if ($lines === false) die("Failed to read file.");

$dir = __DIR__ . '/resources/views/peserta/dashboard';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

// Map of partials to their line ranges (1-indexed, inclusive)
$partials = [
    '_gps-widget' => [166, 185],
    '_stats' => [191, 212], // Actually we can combine Progress Card and Logbook Card into stats? Or just Progress. Let's do Progress + Logbook? No, let's just make _stats = Progress Card. And _logbook-card?
    '_absensi-card' => [214, 242],
    '_lamaran-list' => [338, 737] // We need to verify end line of lamaran-list.
];

// Let's verify end of lamaran-list first.
// The file has 740 lines.
// Line 738:         </div>
// Line 739:     </div>
// Line 740: </x-app-layout>

// Let's extract them in reverse order so line numbers don't mess up during replacement.
// Wait, I can just replace the lines with the include statement.

function replaceLinesWithInclude(&$lines, $start, $end, $includeName, $partialPath) {
    $startIdx = $start - 1;
    $endIdx = $end - 1;
    
    // Extract content
    $content = array_slice($lines, $startIdx, $endIdx - $startIdx + 1);
    file_put_contents($partialPath, implode("", $content));
    
    // Replace with include
    array_splice($lines, $startIdx, $endIdx - $startIdx + 1, ["                    @include('peserta.dashboard.$includeName')\n"]);
}

// Because replacing changes indices, we must do it from BOTTOM to TOP.
replaceLinesWithInclude($lines, 338, 737, '_lamaran-list', "$dir/_lamaran-list.blade.php");
// Wait, the stats grid has Progress Card, Attendance Stats Card, Logbook Card.
// Let's extract Progress Card as _stats, Attendance as _absensi-card, Logbook as _logbook-card. But the plan said just _stats and _absensi-card.
// I will extract 191-212 as _stats and 244-274 as _logbook-card (or merge with _stats). 
// Let's extract 244-274 as _logbook-card for cleanliness.
replaceLinesWithInclude($lines, 244, 274, '_logbook-card', "$dir/_logbook-card.blade.php");
replaceLinesWithInclude($lines, 214, 242, '_absensi-card', "$dir/_absensi-card.blade.php");
replaceLinesWithInclude($lines, 191, 212, '_stats', "$dir/_stats.blade.php");
replaceLinesWithInclude($lines, 166, 185, '_gps-widget', "$dir/_gps-widget.blade.php");

file_put_contents($filePath, implode("", $lines));
echo "Successfully extracted dashboard partials!\n";

