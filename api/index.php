<?php

// 1. Siapkan direktori /tmp untuk cache, sessions, dan view compiler Laravel
$dirs = [
    '/tmp/views',
    '/tmp/cache',
    '/tmp/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/app/private',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 2. Forward request ke index publik Laravel
require __DIR__ . '/../public/index.php';
