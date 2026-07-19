<?php

$file = __DIR__ . '/resources/views/layouts/navigation.blade.php';
$content = file_get_contents($file);

$dir = __DIR__ . '/resources/views/layouts/partials/nav';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

// Map of role to regex pattern
$roles = [
    'peserta' => '/@if\(Auth::user\(\)->role == \'peserta\'\)\s*(<div class="space-y-1\.5">.*?<\/div>)\s*@elseif/s',
    'admin_kota' => '/@elseif\(Auth::user\(\)->role == \'admin_kota\'\)\s*(<div class="space-y-1\.5">.*?<\/div>)\s*@elseif/s',
    'admin_instansi' => '/@elseif\(Auth::user\(\)->role == \'admin_instansi\'\)\s*(<div class="space-y-1\.5">.*?<\/div>)\s*@elseif/s',
    'pembimbing_lapangan' => '/@elseif\(Auth::user\(\)->role == \'pembimbing_lapangan\'\)\s*(<div class="space-y-1\.5">.*?<\/div>)\s*@elseif/s',
    'pembimbing' => '/@elseif\(Auth::user\(\)->role == \'pembimbing\'\)\s*(<div class="space-y-1\.5">.*?<\/div>)\s*@endif/s'
];

foreach ($roles as $role => $pattern) {
    if (preg_match($pattern, $content, $matches)) {
        $navContent = $matches[1];
        file_put_contents("$dir/_{$role}.blade.php", $navContent);
        echo "Extracted $role\n";
    }
}

// Now replace in navigation.blade.php
$content = preg_replace('/@if\(Auth::user\(\)->role == \'peserta\'\).*?@endif/s', 
    "@if(Auth::user()->role == 'peserta')
        @include('layouts.partials.nav._peserta')
    @elseif(Auth::user()->role == 'admin_kota')
        @include('layouts.partials.nav._admin_kota')
    @elseif(Auth::user()->role == 'admin_instansi')
        @include('layouts.partials.nav._admin_instansi')
    @elseif(Auth::user()->role == 'pembimbing_lapangan')
        @include('layouts.partials.nav._pembimbing_lapangan')
    @elseif(Auth::user()->role == 'pembimbing')
        @include('layouts.partials.nav._pembimbing')
    @endif", 
$content);

file_put_contents($file, $content);
echo "Updated navigation.blade.php\n";

