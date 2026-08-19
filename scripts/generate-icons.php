<?php

/**
 * Generates app icons from public/images/Banjarmasin_Logo.svg.png:
 *   - public/favicon.ico            (multi-size ICO, 16/32/48)
 *   - public/images/icon-192.png    (true square 192x192, maskable-safe)
 *   - public/images/icon-512.png    (true square 512x512, maskable-safe)
 *
 * Uses only PHP GD. The source logo is 512x768 (portrait), so to keep the
 * emblem fully visible inside the maskable safe zone (80% circle) we:
 *   1. Fit the logo into the central 80% of a square canvas (logo width = 0.8*size).
 *   2. Place it on a solid #0d9488 (teal-600, the app theme color) background.
 */

$srcPath = __DIR__ . '/../public/images/Banjarmasin_Logo.svg.png';
$outDir  = __DIR__ . '/../public/images';

if (!file_exists($srcPath)) {
    fwrite(STDERR, "Source logo not found: $srcPath\n");
    exit(1);
}

$src = imagecreatefrompng($srcPath);
if (!$src) {
    fwrite(STDERR, "Failed to decode PNG: $srcPath\n");
    exit(1);
}

$srcW = imagesx($src);
$srcH = imagesy($src);

/**
 * Render a square icon of $size px:
 * - background #0d9488
 * - logo scaled to occupy central 80% (maskable safe zone)
 */
function makeSquareIcon($src, $srcW, $srcH, $size): GdImage
{
    $img = imagecreatetruecolor($size, $size);

    // Background #0d9488
    $bg = imagecolorallocate($img, 0x0d, 0x94, 0x88);
    imagefilledrectangle($img, 0, 0, $size - 1, $size - 1, $bg);

    // Fit logo into 80% safe zone, preserving aspect ratio
    $targetW = (int) round($size * 0.80);
    $targetH = (int) round($targetW * ($srcH / $srcW));
    if ($targetH > $size * 0.80) {
        $targetH = (int) round($size * 0.80);
        $targetW = (int) round($targetH * ($srcW / $srcH));
    }
    $dstX = (int) round(($size - $targetW) / 2);
    $dstY = (int) round(($size - $targetH) / 2);

    imagecopyresampled($img, $src, $dstX, $dstY, 0, 0, $targetW, $targetH, $srcW, $srcH);
    return $img;
}

// --- favicon.ico (16, 32, 48) ------------------------------------------------
$icoSizes = [16, 32, 48];
$ico = new SplFileObject($outDir . '/favicon.ico', 'wb');
$ico->fwrite("\x00\x00\x01\x00");               // reserved, type=1
$ico->fwrite(pack('v', count($icoSizes)));      // image count

$imageData = [];
$offset    = 6 + 16 * count($icoSizes);

foreach ($icoSizes as $size) {
    $img = makeSquareIcon($src, $srcW, $srcH, $size);

    // PNG-encode the frame (modern ICOs embed PNG)
    ob_start();
    imagepng($img);
    $png = ob_get_clean();

    $wByte = $size >= 256 ? 0 : $size;
    $hByte = $size >= 256 ? 0 : $size;
    $ico->fwrite(pack('C4vvVV', $wByte, $hByte, 0, 0, 1, 32, strlen($png), $offset));
    $imageData[] = $png;
    $offset += strlen($png);
    imagedestroy($img);
}

foreach ($imageData as $png) {
    $ico->fwrite($png);
}
unset($ico);
echo "Wrote public/images/favicon.ico\n";

// --- PWA icons ---------------------------------------------------------------
foreach ([192, 512] as $size) {
    $img = makeSquareIcon($src, $srcW, $srcH, $size);
    imagepng($img, "$outDir/icon-$size.png");
    imagedestroy($img);
    echo "Wrote public/images/icon-$size.png\n";
}

imagedestroy($src);
echo "Done.\n";
