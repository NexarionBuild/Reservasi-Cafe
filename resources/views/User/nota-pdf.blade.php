<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Transaksi #{{ $transaksi->id_transaksi }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.4; }
        .invoice-box { max-width: 100%; margin: auto; padding: 10px; }
        .header { border-bottom: 2px solid #8B5A2B; padding-bottom: 10px; margin-bottom: 20px; }
        .title { font-size: 22px; font-weight: bold; color: #8B5A2B; }
        .meta-table, .item-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .meta-table td { vertical-align: top; width: 50%; font-size: 13px; }
        .item-table th { background: #f4f4f4; padding: 8px; text-align: left; font-size: 12px; border-bottom: 2px solid #ddd; }
        .item-table td { padding: 10px 8px; border-bottom: 1px solid #eee; }
        .total-style { text-align: right; font-size: 16px; margin-top: 15px; font-weight: bold; color: #8B5A2B; }
        .badge-reservasi { background: #fdf6e2; border: 1px solid #f5dba6; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 13px; }
    </style>
</head>
<body>

<div class="invoice-box">
    <div class="header">
        <table style="width: 100%">
            <tr>
                <td class="title">Cafe Kopi Satu Bang HGL</td>
                <td style="text-align: right; color: #666; font-size: 12px;">NOTA DETAIL TRANSAKSI</td>
            </tr>
        </table>
    </div>

    <!-- Info Transaksi -->
    <table class="meta-table">
        <tr>
            <td>
                <strong>Detail Pengorder:</strong><br>
                ID Pelanggan: #{{ $transaksi->id_pelanggan }}<br>
                Metode Bayar: {{ strtoupper($transaksi->metode_pembayaran) }}
            </td>
            <td style="text-align: right;">
                <strong>ID. TRANSAKSI:</strong> #{{ $transaksi->id_transaksi }}<br>
                <strong>Tanggal:</strong> {{ date('d M Y, H:i', strtotime($transaksi->created_at)) }} WIB<br>
                <strong>Status:</strong> <span style="color: green;">{{ strtoupper($transaksi->status == 'tervalidasi' ? 'DP':$transaksi->status) }}</span>
            </td>
        </tr>
    </table>

    <!-- Info Tempat/Meja (Jika ada Reservasi) -->
    @if($reservasi)
    <div class="badge-reservasi">
        <strong>Informasi Tempat & Reservasi:</strong><br>
        • ID RESERVASI: #{{ $reservasi->id_reservasi }}<br>
        • Ruangan: {{ $ruangan == 'Indoor' ? 'indoor':$ruangan }}<br>
        • Nomor Meja: {{ $meja ?? '-' }}<br>
        • Tanggal Reservasi: {{ date('d M Y', strtotime($reservasi->tanggal)) }}
    </div>
    @endif

    <!-- Daftar Pesanan Menu -->
    <table class="item-table">
        <thead>
            <tr>
                <th>Menu Pesanan</th>
                <th style="text-align: center; width: 50px;">Qty</th>
                <th style="text-align: right; width: 100px;">Harga</th>
                <th style="text-align: right; width: 120px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pesanan as $item)
            <tr>
                <td><strong>{{ $item['nama'] }}</strong></td>
                <td style="text-align: center;">{{ $item['qty'] }}</td>
                <td style="text-align: right;">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                <td style="text-align: right;">Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-style">
        Total Bayar: Rp {{ number_format($totalBayar, 0, ',', '.') }}
    </div>
</div>

</body>
</html>