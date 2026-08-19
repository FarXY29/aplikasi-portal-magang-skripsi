<?php

// Try lossless PNG re-encode (GD, compression 9) on the frame images.
// Only replaces originals if the re-encoded file is strictly smaller.
$targets = [
    __DIR__ . '/../public/images/certificate_frame.png',
    __DIR__ . '/../public/images/id_card_frame.png',
];

foreach ($targets as $path) {
    $orig = filesize($path);
    $im = imagecreatefrompng($path);
    if (!$im) {
        echo basename($path), ": decode failed, skipped\n";
        continue;
    }
    $tmp = $path . '.opt.png';
    imagepng($im, $tmp, 9);
    imagedestroy($im);
    $new = filesize($tmp);
    $pct = $orig > 0 ? round((1 - $new / $orig) * 100, 1) : 0;
    printf("%-28s orig=%8d  optimized=%8d  %s (%s%%)\n", basename($path), $orig, $new, $new < $orig ? 'SMALLER' : 'not smaller', $pct);
    if ($new < $orig) {
        rename($tmp, $path);
        echo "  -> replaced with optimized version\n";
    } else {
        unlink($tmp);
        echo "  -> kept original\n";
    }
}
echo "Done.\n";
