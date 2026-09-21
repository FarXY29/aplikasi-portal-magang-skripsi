{{-- Ringkasan Statistik 6 Status (Formal Government Box Grid) --}}
<div class="section-title">Ringkasan Statistik Pendaftaran &amp; Status Pelacakan Real-Time</div>
<table class="stats-table">
    <tr>
        <td style="width: 16.66%;">
            <div class="stat-header">Total Pendaftar</div>
            <div class="stat-value stat-total">{{ number_format($stats['total'] ?? 0) }}</div>
        </td>
        <td style="width: 16.66%;">
            <div class="stat-header">Pending (Baru)</div>
            <div class="stat-value stat-pending">{{ number_format($stats['pending'] ?? 0) }}</div>
        </td>
        <td style="width: 16.66%;">
            <div class="stat-header">Daftar Tunggu</div>
            <div class="stat-value stat-menunggu">{{ number_format($stats['menunggu'] ?? 0) }}</div>
        </td>
        <td style="width: 16.66%;">
            <div class="stat-header">Diterima / Aktif</div>
            <div class="stat-value stat-diterima">{{ number_format($stats['diterima'] ?? 0) }}</div>
        </td>
        <td style="width: 16.66%;">
            <div class="stat-header">Ditolak</div>
            <div class="stat-value stat-ditolak">{{ number_format($stats['ditolak'] ?? 0) }}</div>
        </td>
        <td style="width: 16.66%;">
            <div class="stat-header">Selesai Magang</div>
            <div class="stat-value stat-selesai">{{ number_format($stats['selesai'] ?? 0) }}</div>
        </td>
    </tr>
</table>
