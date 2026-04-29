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
.ttd-area { display: table; width: 100%; margin-top: 15px; }
.ttd-col { display: table-cell; text-align: center; vertical-align: bottom; width: 33%; padding: 0 5px; }
.ttd-box img.ttd-img { height: 45px; margin-bottom: 2px; }
.ttd-box img.cap-img { height: 35px; opacity: 0.7; margin-bottom: 2px; }
.ttd-box .garis { border-top: 1px solid #333; padding-top: 3px; font-size: 10px; font-weight: bold; }
.ttd-box .jabatan { font-size: 9px; color: #666; }
.qr-ttd { text-align: center; padding: 4px; border: 1px dashed #aaa; border-radius: 4px; margin-bottom: 4px; }
.qr-ttd table.qr-html { border-collapse: collapse; margin: 0 auto 2px; }
.qr-ttd table.qr-html td { width: 3px; height: 3px; padding: 0; border: none; }
.qr-ttd .lbl { font-size: 7px; color: #888; font-style: italic; }
.qr-ttd .kode { font-size: 6px; color: #bbb; font-family: monospace; }
.qr-dokumen { text-align: center; padding: 4px; }
.qr-dokumen table.qr-html { border-collapse: collapse; margin: 0 auto 2px; }
.qr-dokumen table.qr-html td { width: 3px; height: 3px; padding: 0; border: none; }
.qr-dokumen .lbl { font-size: 7px; color: #888; }
.hash-val { font-size: 7px; color: #bbb; font-family: monospace; margin-top: 3px; }
.footer { text-align: center; font-size: 10px; color: #999; margin-top: 10px; border-top: 1px solid #eee; padding-top: 5px; }
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

    <div class="ttd-area">
        {{-- Kolom Bendahara --}}
        <div class="ttd-col">
            <div class="ttd-box">
                <div style="font-size:10px;color:#666;margin-bottom:4px">Penerima,</div>
                @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['ttd_bendahara']))
                <img class="ttd-img" src="{{ $imgs['ttd_bendahara'] }}">
                @endif
                @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['cap']))
                <img class="cap-img" src="{{ $imgs['cap'] }}">
                @endif
                @if(in_array($modeTtd, ['qr','keduanya']) && $qrBendahara)
                <div class="qr-ttd">
                    {!! $qrBendahara !!}
                    <div class="lbl">TTD Digital Bendahara</div>
                </div>
                @endif
                <div class="garis">{{ $setting->nama_bendahara ?? 'Bendahara' }}</div>
                <div class="jabatan">Bendahara</div>
            </div>
        </div>

        {{-- Kolom Tengah: QR Dokumen --}}
        <div class="ttd-col">
            <div class="qr-dokumen">
                {!! $qrDokumen !!}
                <div class="lbl">Verifikasi Dokumen</div>
                @if($pembayaran->hash_dokumen)
                <div class="hash-val">{{ $pembayaran->hash_dokumen }}</div>
                @endif
            </div>
        </div>

        {{-- Kolom Ketua --}}
        <div class="ttd-col">
            <div class="ttd-box">
                <div style="font-size:10px;color:#666;margin-bottom:4px">Mengetahui,</div>
                @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['ttd_ketua']))
                <img class="ttd-img" src="{{ $imgs['ttd_ketua'] }}">
                @endif
                @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['cap']))
                <img class="cap-img" src="{{ $imgs['cap'] }}">
                @endif
                @if(in_array($modeTtd, ['qr','keduanya']) && $qrKetua)
                <div class="qr-ttd">
                    {!! $qrKetua !!}
                    <div class="lbl">TTD Digital Ketua Umum</div>
                </div>
                @endif
                <div class="garis">{{ $setting->nama_ketua ?? 'Ketua' }}</div>
                <div class="jabatan">Ketua Umum</div>
            </div>
        </div>
    </div>

    <div class="footer">{{ $setting->nama_asosiasi ?? '' }} | {{ $setting->website ?? '' }}</div>
</div>
</body>
</html>
