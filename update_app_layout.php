<?php

$file = __DIR__ . '/resources/views/layouts/app.blade.php';
$content = file_get_contents($file);

// 1. Replace the MOBILE BOTTOM NAVIGATION BAR block
// It starts with <!-- MOBILE BOTTOM NAVIGATION BAR (Android & iOS Phones < md) -->
// It ends with </nav>
$navPattern = '/<!-- MOBILE BOTTOM NAVIGATION BAR.*?<\/nav>/s';
$navReplacement = "@include('layouts.partials._mobile-bottom-nav')";
$content = preg_replace($navPattern, $navReplacement, $content, 1);

// 2. Replace the MOBILE iOS/Android BOTTOM SHEET MENU block
// It starts with <!-- MOBILE iOS/Android BOTTOM SHEET MENU (`md:hidden`) -->
// It ends exactly at the div before <script src="//instant.page
$sheetPattern = '/<!-- MOBILE iOS\/Android BOTTOM SHEET MENU.*?<\/div>\s*<\/div>\s*(?=<script src="\/\/instant\.page)/s';
$sheetReplacement = "@include('layouts.partials._mobile-sheet')\n    ";
$content = preg_replace($sheetPattern, $sheetReplacement, $content, 1);

// 3. Add <x-ui.confirm-dialog /> before @stack('scripts')
$content = str_replace("@stack('scripts')", "<x-ui.confirm-dialog />\n    @stack('scripts')", $content);

file_put_contents($file, $content);
echo "Successfully updated app.blade.php with preg_replace!\n";
