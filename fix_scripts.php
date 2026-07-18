<?php
$lamaranFile = __DIR__ . '/resources/views/peserta/dashboard/_lamaran-list.blade.php';
$dashFile = __DIR__ . '/resources/views/peserta/dashboard.blade.php';

$lamaranLines = file($lamaranFile);
// We want to remove lines from 244 to the end.
// 244 is index 243.
$scriptLines = array_slice($lamaranLines, 243);
$lamaranLines = array_slice($lamaranLines, 0, 243);
file_put_contents($lamaranFile, implode("", $lamaranLines));

// In dashboard.blade.php, we want to replace:
//     </script>
//     @endpush
// with the full script block + the closing tags.
$dashLines = file($dashFile);
$newDashLines = [];
foreach ($dashLines as $line) {
    if (trim($line) == '</script>') {
        // Insert the script lines before this, except we also want to remove the dangling closing tags.
        // Actually, $scriptLines ALREADY contains @push('scripts') and <script>, but NO </script> and @endpush.
        // So we can just insert $scriptLines right before </script>.
        foreach ($scriptLines as $sl) {
            $newDashLines[] = $sl;
        }
        $newDashLines[] = $line; // </script>
    } else {
        $newDashLines[] = $line;
    }
}
file_put_contents($dashFile, implode("", $newDashLines));
echo "Fixed script block!\n";
