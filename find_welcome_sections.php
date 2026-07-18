<?php
$lines = file(__DIR__ . '/resources/views/public/welcome.blade.php');
foreach ($lines as $index => $line) {
    if (strpos($line, '<!-- Hero Section -->') !== false) { echo "Hero: " . ($index + 1) . "\n"; }
    if (strpos($line, '<section class="relative z-20 -mt-10 sm:-mt-16 px-4 w-full">') !== false) { echo "Stats: " . ($index + 1) . "\n"; }
    if (strpos($line, '<section id="langkah"') !== false) { echo "Langkah: " . ($index + 1) . "\n"; }
    if (strpos($line, '<!-- Global Layout Background -->') !== false) { echo "Main Layout BG: " . ($index + 1) . "\n"; }
    if (strpos($line, '<h2 id="lowongan"') !== false) { echo "Lowongan: " . ($index + 1) . "\n"; }
    if (strpos($line, '<section id="faq"') !== false) { echo "FAQ: " . ($index + 1) . "\n"; }
    if (strpos($line, '<!-- Footer Section -->') !== false || strpos($line, '<!-- Footer -->') !== false) { echo "Footer: " . ($index + 1) . "\n"; }
    if (strpos($line, '<!-- Bottom Quick Navigation (Mobile Only) -->') !== false) { echo "Mobile Nav: " . ($index + 1) . "\n"; }
    if (strpos($line, '</body>') !== false) { echo "Body end: " . ($index + 1) . "\n"; }
}
