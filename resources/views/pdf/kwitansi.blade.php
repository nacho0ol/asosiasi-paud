 <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
@page { margin: 15px; }
body { font-family: Arial, sans-serif; font-size: 12px; }

.kwitansi { width: 100%; border: 2px solid #1a3c5e; padding: 12px; }

.header { display: flex; align-items: center; border-bottom: 2px solid #1a3c5e; padding-bottom: 8px; margin-bottom: 8px; }
.header img { height: 50px; margin-right: 12px; }
.header-text .org { font-size: 15px; font-weight: bold; color: #1a3c5e; }
.header-text .sub { font-size: 10px; color: #555; }

.judul { text-align: center; font-size: 17px; font-weight: bold; text-transform: uppercase; letter-spacing: 3px; color: #1a3c5e; margin: 6px 0 4px 0; }
.no-kwitansi { text-align: right; font-size: 11px; color: #666; margin-bottom: 6px; }

table.detail { width: 100%; border-collapse: collapse; margin: 6px 0; }
table.detail td { padding: 4px 8px; border: 1px solid #ddd; }
table.detail td:first-child { background: #f4f6f9; font-weight: bold; width: 35%; }

.jumlah-box { background: #1a3c5e; color: white; text-align: center; padding: 8px; font-size: 16px; font-weight: bold; margin: 6px 0; border-radius: 4px; }

.footer { text-align: center; font-size: 10px; color: #999; margin-top: 8px; border-top: 1px solid #eee; padding-top: 5px; }
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

    <table width="100%" style="margin-top: 10px; border-collapse: collapse;">
        <tr>
            <td width="60%" style="border: none;"></td>
            <td width="40%" style="border: none; text-align: center; vertical-align: top;">
                <div style="font-size:10px; color:#666; margin-bottom:2px;">Penerima,</div>
                <div style="font-size:11px; font-weight:bold; margin-bottom:4px;">Bendahara</div>

                <div style="position: relative; width: 130px; height: 70px; margin: 0 auto 6px auto;">
                    @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['cap']))
                        <img src="{{ $imgs['cap'] }}"
                             style="position: absolute; top: 0; left: 0;
                                    width: 130px; height: 70px;
                                    object-fit: contain; opacity: 0.5;">
                    @endif
                    @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['ttd_bendahara']))
                        <img src="{{ $imgs['ttd_bendahara'] }}"
                             style="position: absolute; top: 14px; left: 50%;
                                    transform: translateX(-50%);
                                    width: 80px; height: 42px;
                                    object-fit: contain;">
                    @endif
                </div>

                <div style="border-top: 1px solid #333; font-weight: bold; font-size: 11px; padding-top: 3px; display: inline-block; min-width: 140px;">
                    {{ $setting->nama_bendahara ?? 'Bendahara' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">{{ $setting->nama_asosiasi ?? '' }} | {{ $setting->website ?? '' }}</div>

</div>
</body>
</html>