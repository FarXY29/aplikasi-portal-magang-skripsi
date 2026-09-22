<script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->get_font("times-roman", "normal");
        $size = 8;
        
        // Nomor Halaman (Kanan Bawah)
        $pageText = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
        $textWidth = $fontMetrics->getTextWidth($pageText, $font, $size);
        $pdf->page_text($pdf->get_width() - 40 - $textWidth, $pdf->get_height() - 25, $pageText, $font, $size, [0.2, 0.2, 0.2]);
        
        // Catatan Kaki Resmi Sistem (Kiri Bawah)
        $footerNote = "Dokumen Resmi Portal Magang Pemerintah Kota Banjarmasin - Dicetak pada " . date('d/m/Y H:i') . " WITA";
        $pdf->page_text(40, $pdf->get_height() - 25, $footerNote, $font, $size, [0.2, 0.2, 0.2]);
    }
</script>

