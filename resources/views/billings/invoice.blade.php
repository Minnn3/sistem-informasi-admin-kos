<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Tagihan Kos</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 12px; }
        .details-table { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .details-table th, .details-table td { padding: 10px 5px; text-align: left; vertical-align: top; border-bottom: 1px solid #eee; }
        .details-table th { width: 130px; font-weight: normal; color: #666; }
        .details-table td { font-weight: bold; }
        .amount-box { border: 2px dashed #000; padding: 20px; text-align: center; margin-bottom: 30px; background-color: #fafafa; }
        .amount-box h2 { margin: 10px 0; font-size: 32px; color: #000; }
        .amount-box span { font-size: 12px; color: #666; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; }
        .status-lunas { color: #fff; background-color: #16a34a; padding: 8px 20px; font-weight: bold; text-transform: uppercase; display: inline-block; font-size: 18px; margin-top: 10px; border-radius: 3px; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 50px; border-top: 1px solid #eee; padding-top: 20px; }
        
        .w-half { width: 50%; float: left; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="header">
        <h1>KUITANSI KOS</h1>
        <p>Tanda Terima Pembayaran Resmi</p>
    </div>

    <div class="w-half">
        <table class="details-table" style="width: 100%;">
            <tr>
                <th>No. Tagihan</th>
                <td>#INV-KOS-{{ str_pad($billing->id, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                 <th>Nama Penyewa</th>
                 <td>{{ $billing->tenant->name }}</td>
            </tr>
            <tr>
                 <th>Nomor Kamar</th>
                 <td>Kamar {{ $billing->room->room_number }} ({{ $billing->room->type }})</td>
            </tr>
        </table>
    </div>
    <div class="w-half">
        <table class="details-table" style="width: 100%;">
            <tr>
                <th>Tanggal Cetak</th>
                <td>{{ date('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Bulan Tagihan</th>
                <td>{{ \Carbon\Carbon::parse($billing->due_date)->translatedFormat('F Y') }}</td>
            </tr>
            <tr>
                 <th>Tgl Pelunasan</th>
                 <td>{{ $billing->paid_at ? \Carbon\Carbon::parse($billing->paid_at)->translatedFormat('d F Y H:i') : '-' }}</td>
            </tr>
        </table>
    </div>
    
    <div class="clear"></div>

    <div class="amount-box">
        <span>TOTAL DIBAYAR</span>
        <h2>Rp {{ number_format($billing->amount, 0, ',', '.') }}</h2>
        @if($billing->status === 'paid')
            <div class="status-lunas">Telah Lunas</div>
        @else
            <div style="color: #fff; background-color: #dc2626; padding: 8px 20px; font-weight: bold; text-transform: uppercase; display: inline-block; font-size: 18px; margin-top: 10px; border-radius: 3px;">Belum Lunas</div>
        @endif
    </div>

    <div class="footer">
        <p>Kuitansi ini dicetak secara otomatis oleh sistem Kos Management pada {{ date('d M Y H:i:s') }}</p>
        <p>Dokumen ini sah sebagai tanda bukti pembayaran apabila status menunjukkan 'LUNAS'.</p>
    </div>
</body>
</html>
