<?php
$file = __DIR__ . '/resources/views/public/welcome.blade.php';
$content = file_get_contents($file);
$lines = explode("\n", $content);

$heroStart = 212; // 0-indexed, so 213 is index 212
$statsStart = 259; // 260 is index 259
$alurStart = 297; // 298 is index 297
$lowonganStart = 361; // 362 is index 361
$faqStart = 844; // 845 is index 844
$footerStart = 890; // 891 is index 890
$end = count($lines) - 2; // exclude body and html closing tags if any, but wait, let's keep it till the end before </body>

// Wait, the body end is at 1013 (index 1012). Let's extract them precisely.
function extract_lines($lines, $start, $end) {
    return implode("\n", array_slice($lines, $start, $end - $start));
}

$heroContent = extract_lines($lines, $heroStart, $statsStart);
$statsContent = extract_lines($lines, $statsStart, $alurStart);
$alurContent = extract_lines($lines, $alurStart, $lowonganStart);
$lowonganContent = extract_lines($lines, $lowonganStart, $faqStart);
$faqContent = extract_lines($lines, $faqStart, $footerStart);
$footerContent = extract_lines($lines, $footerStart, count($lines) - 2);

// Reconstruct welcome.blade.php
$welcomeTop = extract_lines($lines, 0, $heroStart);
$welcomeBottom = "\n</body>\n</html>";

$newWelcome = $welcomeTop . "\n";
$newWelcome .= "    @include('public.welcome._hero')\n";
$newWelcome .= "    @include('public.welcome._stats')\n";
$newWelcome .= "    @include('public.welcome._alur-magang')\n";
$newWelcome .= "    @include('public.welcome._lowongan-grid')\n";
$newWelcome .= "    @include('public.welcome._faq')\n";
$newWelcome .= "    @include('public.welcome._footer')\n";
$newWelcome .= $welcomeBottom;

$dir = __DIR__ . '/resources/views/public/welcome';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

file_put_contents($dir . '/_hero.blade.php', $heroContent);
file_put_contents($dir . '/_stats.blade.php', $statsContent);
file_put_contents($dir . '/_alur-magang.blade.php', $alurContent);
file_put_contents($dir . '/_lowongan-grid.blade.php', $lowonganContent);
file_put_contents($dir . '/_faq.blade.php', $faqContent);
file_put_contents($dir . '/_footer.blade.php', $footerContent);

file_put_contents($file, $newWelcome);

echo "Successfully split welcome.blade.php into partials.\n";
