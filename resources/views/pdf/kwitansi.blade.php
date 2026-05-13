<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: Arial, sans-serif; font-size: 12px; }
.kwitansi { width: 100%; border: 2px solid #1a3c5e; padding: 15px; }
.header { display: flex; align-items: center; border-bottom: 2px solid #1a3c5e; padding-bottom: 10px; margin-bottom: 10px; }
.header img { height: 50px; margin-right: 12px; }
.header-text .org { font-size: 15px; font-weight: bold; color: #1a3c5e; }
.header-text .sub { font-size: 10px; color: #555; }
.judul { text-align: center; font-size: 18px; font-weight: bold; text-transform: uppercase; letter-spacing: 3px; color: #1a3c5e; margin: 10px 0; }
.no-kwitansi { text-align: right; font-size: 11px; color: #666; margin-bottom: 10px; }
table.detail { width: 100%; border-collapse: collapse; margin: 10px 0; }
table.detail td { padding: 5px 8px; border: 1px solid #ddd; }
table.detail td:first-child { background: #f4f6f9; font-weight: bold; width: 35%; }
.jumlah-box { background: #1a3c5e; color: white; text-align: center; padding: 10px; font-size: 16px; font-weight: bold; margin: 10px 0; border-radius: 4px; }

/* Layout Footer Baru */
.footer-area { margin-top: 30px; width: 100%; }
.ttd-col { float: right; width: 40%; text-align: center; }
.clearfix { clear: both; }
.footer { text-align: center; font-size: 10px; color: #999; margin-top: 10px; border-top: 1px solid #eee; padding-top: 5px; clear: both; }
</style>
</head>
<body>
<div class="kwitansi">
    <div class="header">
        @if(!empty($imgs['logo']))
        <img src="{{ $imgs['logo'] }}">
        @endif
        <div class="header-text">
            <div class="org">{{ $setting->nama_asosiasi ?? 'Asosiasi Dosen PAUD Indonesia' }}</div>
            <div class="sub">{{ $setting->alamat ?? '' }}</div>
            <div class="sub">{{ $setting->email ?? '' }} | {{ $setting->telepon ?? '' }}</div>
        </div>
    </div>
    <div class="judul">Kwitansi Pembayaran</div>
    <div class="no-kwitansi">No: <strong>{{ $pembayaran->no_kwitansi }}</strong></div>
    <table class="detail">
        <tr><td>Tanggal</td><td>{{ $pembayaran->tanggal_bayar->format('d F Y') }}</td></tr>
        <tr><td>Jenis Member</td><td>{{ ucfirst($pembayaran->jenis) }}</td></tr>
        @if($pembayaran->jenis === 'dosen')
        <tr><td>Nama Dosen</td><td>{{ $pembayaran->memberDosen->dosen->nama ?? '-' }}</td></tr>
        <tr><td>NIDN</td><td>{{ $pembayaran->memberDosen->dosen->nidn ?? '-' }}</td></tr>
        <tr><td>No Member</td><td>{{ $pembayaran->memberDosen->no_member ?? '-' }}</td></tr>
        @else
        <tr><td>Nama Prodi</td><td>{{ $pembayaran->memberProdi->prodi->nama_prodi ?? '-' }}</td></tr>
        <tr><td>Universitas</td><td>{{ $pembayaran->memberProdi->prodi->nama_universitas ?? '-' }}</td></tr>
        <tr><td>No Member</td><td>{{ $pembayaran->memberProdi->no_member ?? '-' }}</td></tr>
        @endif
        <tr><td>Keterangan</td><td>{{ $pembayaran->keterangan ?: 'Iuran Keanggotaan' }}</td></tr>
        <tr><td>Metode</td><td>{{ ucfirst($pembayaran->metode) }}</td></tr>
    </table>
    <div class="jumlah-box">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</div>

    @php $modeTtd = $setting->mode_ttd ?? 'gambar'; @endphp

    <div class="footer-area">
        <div class="ttd-col">
            <div style="font-size:10px;color:#666;margin-bottom:4px">Penerima,</div>
            <div style="font-size:11px;font-weight:bold;">Bendahara</div>

            <div style="height: 80px; margin: 10px 0;">
                @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['cap']))
                    <img src="{{ $imgs['cap'] }}" style="height: 75px; opacity: 0.5;">
                @endif

                @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['ttd_bendahara']))
                    <img src="{{ $imgs['ttd_bendahara'] }}" style="height: 50px; margin-left: -50px; margin-bottom: 10px;">
                @endif
            </div>

            <div style="border-top: 1px solid #333; font-weight: bold; font-size: 11px; padding-top: 3px; display: inline-block; min-width: 150px;">
                {{ $setting->nama_bendahara ?? 'Bendahara' }}
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="footer">{{ $setting->nama_asosiasi ?? '' }} | {{ $setting->website ?? '' }}</div>
</div>
</body>
</html>