<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Times New Roman', serif; background: white; }
.piagam { width: 100%; min-height: 190mm; border: 8px double #8B6914; padding: 20px; text-align: center; }
.inner { border: 2px solid #8B6914; padding: 20px; min-height: 170mm; }
.logo { height: 70px; margin-bottom: 10px; background: white; border-radius: 6px; padding: 4px 10px; object-fit: contain; }
.org-name { font-size: 18px; font-weight: bold; color: #1a3c5e; text-transform: uppercase; letter-spacing: 2px; }
.org-sub { font-size: 12px; color: #555; margin-bottom: 15px; }
.judul { font-size: 28px; font-weight: bold; color: #8B6914; text-transform: uppercase; letter-spacing: 4px; margin: 15px 0; border-top: 2px solid #8B6914; border-bottom: 2px solid #8B6914; padding: 8px 0; }
.diberikan { font-size: 13px; color: #333; margin: 10px 0; }
.nama-prodi { font-size: 22px; font-weight: bold; color: #1a3c5e; margin: 8px 0; }
.universitas { font-size: 14px; color: #555; margin-bottom: 10px; }
.isi { font-size: 12px; color: #333; line-height: 1.8; margin: 10px 30px; }
.no-member { font-size: 11px; color: #666; margin: 8px 0; }
.berlaku { font-size: 11px; color: #333; margin: 5px 0; }
.tanggal { font-size: 11px; color: #555; margin-top: 10px; }
.ttd-area { display: table; width: 100%; margin-top: 20px; }
.ttd-col { display: table-cell; text-align: center; vertical-align: bottom; width: 33%; padding: 0 8px; }
.ttd-box img.ttd-img { height: 50px; margin-bottom: 2px; }
.ttd-box .garis { border-top: 1px solid #333; padding-top: 3px; font-size: 11px; font-weight: bold; }
.ttd-box .jabatan { font-size: 10px; color: #666; }
.qr-ttd { text-align: center; padding: 4px; border: 1px dashed #8B6914; border-radius: 4px; margin-bottom: 4px; display: inline-block; }
.qr-ttd table.qr-html { border-collapse: collapse; margin: 0 auto 2px; }
.qr-ttd table.qr-html td { width: 3px; height: 3px; padding: 0; border: none; }
.qr-ttd .lbl { font-size: 7px; color: #8B6914; font-style: italic; }
.qr-dokumen { text-align: center; padding: 4px; }
.qr-dokumen table.qr-html { border-collapse: collapse; margin: 0 auto 2px; }
.qr-dokumen table.qr-html td { width: 3px; height: 3px; padding: 0; border: none; }
.qr-dokumen .lbl { font-size: 8px; color: #888; }
.hash-val { font-size: 7px; color: #bbb; font-family: monospace; margin-top: 2px; }
</style>
</head>
<body>
<div class="piagam">
    <div class="inner">
        @if(!empty($imgs['logo']))
        <img class="logo" src="{{ $imgs['logo'] }}">
        @endif
        <div class="org-name">{{ $setting->nama_asosiasi ?? 'Asosiasi Dosen PAUD Indonesia' }}</div>
        <div class="org-sub">{{ $setting->singkatan ?? '' }} | {{ $setting->alamat ?? '' }}</div>
        <div class="judul">Piagam Keanggotaan</div>
        <div class="diberikan">Diberikan kepada:</div>
        <div class="nama-prodi">{{ $memberProdi->prodi->nama_prodi }}</div>
        <div class="universitas">{{ $memberProdi->prodi->nama_universitas }}</div>
        <div class="isi">
            Telah terdaftar sebagai <strong>Anggota Resmi</strong><br>
            {{ $setting->nama_asosiasi ?? 'Asosiasi Dosen PAUD Indonesia' }}<br>
            dan berhak mendapatkan seluruh hak dan fasilitas keanggotaan.
        </div>
        <div class="no-member">Nomor Anggota: <strong>{{ $memberProdi->no_member }}</strong></div>
        <div class="berlaku">Berlaku: {{ $memberProdi->tanggal_mulai->format('d F Y') }} s/d {{ $memberProdi->tanggal_berakhir->format('d F Y') }}</div>
        <div class="tanggal">Ditetapkan pada tanggal {{ now()->format('d F Y') }}</div>

        @php $modeTtd = $setting->mode_ttd ?? 'gambar'; @endphp

        <div class="ttd-area">
            {{-- Ketua Umum --}}
            <div class="ttd-col">
                <div class="ttd-box">
                    @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['ttd_ketua']))
                    <img class="ttd-img" src="{{ $imgs['ttd_ketua'] }}">
                    @else
                    <div style="height:50px"></div>
                    @endif
                    @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['cap']))
                    <img class="ttd-img" src="{{ $imgs['cap'] }}" style="height:35px;opacity:0.75;">
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

            {{-- QR Dokumen (tengah) --}}
            <div class="ttd-col">
                <div class="qr-dokumen">
                    {!! $qrDokumen !!}
                    <div class="lbl">Verifikasi Dokumen</div>
                    @if($memberProdi->hash_dokumen)
                    <div class="hash-val">{{ $memberProdi->hash_dokumen }}</div>
                    @endif
                </div>
            </div>

            {{-- Bendahara --}}
            <div class="ttd-col">
                <div class="ttd-box">
                    @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['ttd_bendahara']))
                    <img class="ttd-img" src="{{ $imgs['ttd_bendahara'] }}">
                    @else
                    <div style="height:50px"></div>
                    @endif
                    @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['cap']))
                    <img class="ttd-img" src="{{ $imgs['cap'] }}" style="height:35px;opacity:0.75;">
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
        </div>
    </div>
</div>
</body>
</html>
