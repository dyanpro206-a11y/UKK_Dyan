<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan | Perpustakaan Dyann</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; font-size:  ica 13px; }
        .kop { text-align: center; border-bottom: 2px solid #0f3460; padding-bottom: 14px; margin-bottom: 18px; }
        .kop h3 { color: #0f3460; font-weight: 700; margin-bottom: 4px; }
        .kop p { margin-bottom:  ica 2px; color: #555; }
        .table thead th { background-color: #0f3460 !important; color: #fff !important; font-size: 12px; }
        .badge-status { padding: 4px 10px; border-radius: 20px; font-size: 11px; }
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-3">
        <div class="d-flex justify-content-between align-items-start no-print mb-3">
            <a href="{{ route('laporan.index') }}?{{ http_build_query(request()->query()) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="fas fa-print me-1"></i> Cetak / Simpan PDF
            </button>
        </div>

        <div class="kop">
            <h3>PERPUSTAKAAN DYANN</h3>
            <p>Laporan Peminjaman Buku</p>
            @if ($jenis === 'semua')
                <p><strong>Semua Peminjaman</strong></p>
            @elseif ($jenis === 'dipinjam')
                <p><strong>Peminjaman Sedang Berlangsung</strong></p>
            @else
                <p><strong>Peminjaman Sudah Dikembalikan</strong></p>
            @endif
            @if ($tglAwal || $tglAkhir)
                <p>
                    Periode:
                    @if ($tglAwal)
                        {{ \Carbon\Carbon::parse($tglAwal)->translatedFormat('d F Y') }}
                    @endif
                    @if ($tglAwal && $tglAkhir) - @endif
                    @if ($tglAkhir)
                        {{ \Carbon\Carbon::parse($tglAkhir)->translatedFormat('d F Y') }}
                    @endif
                </p>
            @endif
            <p><small>Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</small></p>
        </div>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Buku</th>
                    <th>Peminjam</th>
                    <th>Tgl. Peminjaman</th>
                    <th>Tgl. Pengembalian</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjaman as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->buku?->judul }}</td>
                        <td>{{ $item->user?->nama_lengkap }}</td>
                        <td>{{ $item->tgl_peminjaman?->format('d M Y') }}</td>
                        <td>{{ $item->tgl_pengembalian?->format('d M Y') ?? '-' }}</td>
                        <td>
                            @if ($item->status_peminjaman === 'Dipinjam')
                                <span class="badge bg-warning text-dark badge-status">Dipinjam</span>
                            @else
                                <span class="badge bg-success text-white badge-status">Dikembalikan</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data laporan dengan filter tersebut.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>