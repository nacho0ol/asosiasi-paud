<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: Arial, sans-serif; background: white; }
.wrapper { width: 297mm; padding: 10mm 15mm; }
.label-row { display: table; width: 100%; margin-bottom: 2mm; }
.label-cell { display: table-cell; width: 50%; text-align: center; font-size: 9pt; color: #888; padding: 0 5mm; }
.row-kartu { display: table; width: 100%; margin-bottom: 8mm; }
.col-kartu { display: table-cell; padding: 0 5mm; vertical-align: top; }

/* Kartu */
.kartu { width: 85.6mm; height: 54mm; position: relative; overflow: hidden; background: #f0f4f4; border-radius: 3mm; }
.bg-teal { position: absolute; bottom: -5mm; left: -5mm; width: 48mm; height: 40mm; background: #2ab5a5; border-radius: 50%; }
.bg-teal-back { position: absolute; bottom: 0; left: 0; width: 40mm; height: 54mm; background: #2ab5a5; border-radius: 0 60% 0 0; }
.acc-gray  { position: absolute; top: 0; right: 26mm; width: 5mm;  height: 60mm; background: #c8d0d0; transform: skewX(-10deg); }
.acc-brown { position: absolute; top: 0; right: 17mm; width: 7mm;  height: 60mm; background: #7a4f1e; transform: skewX(-10deg); }
.acc-yel   { position: absolute; top: 0; right: 8mm;  width: 10mm; height: 60mm; background: #f0a500; transform: skewX(-10deg); }
.acc-gray-l  { position: absolute; top: 0; left: 22mm; width: 5mm;  height: 60mm; background: #c8d0d0; transform: skewX(-10deg); }
.acc-brown-l { position: absolute; top: 0; left: 30mm; width: 7mm;  height: 60mm; background: #7a4f1e; transform: skewX(-10deg); }
.acc-yel-l   { position: absolute; top: 0; left: 39mm; width: 10mm; height: 60mm; background: #f0a500; transform: skewX(-10deg); }
.logo-area { position: absolute; top: 5mm; left: 3mm; width: 36mm; z-index: 10; }
.logo-area .org-name { font-size: 6pt; font-weight: bold; color: #1a3c3c; line-height: 1.3; margin-top: 1mm; }
.logo-area .tagline { font-size: 4.5pt; color: #555; font-style: italic; }
.qr-area { position: absolute; top: 3mm; right: 1mm; width: 19mm; text-align: center; z-index: 10; }
.qr-area table.qr-html { border-collapse: collapse; margin: 0 auto; }
.qr-area table.qr-html td { width: 2px; height: 2px; padding: 0; border: none; }
.qr-area .exp { font-size: 5pt; color: #333; margin-top: 1mm; text-align: center; }
.website-badge { position: absolute; bottom: 8mm; right: 1mm; background: #f0a500; color: white; font-size: 5.5pt; font-weight: bold; padding: 1mm 2.5mm; border-radius: 2mm; z-index: 10; }
.logo-back { position: absolute; bottom: 5mm; left: 3mm; width: 22mm; text-align: center; z-index: 10; }
.logo-back .singkatan { font-size: 7pt; font-weight: bold; color: white; margin-top: 1mm; }
.logo-back .label-kartu { font-size: 5.5pt; color: #d0f0ee; }
.info-area { position: absolute; top: 5mm; right: 2mm; width: 44mm; z-index: 10; }
.nama-badge { background: #f0a500; color: white; font-size: 7.5pt; font-weight: bold; padding: 1.5mm 2mm; border-radius: 2mm; margin-bottom: 1.5mm; text-align: center; }
.no-anggota { font-size: 6pt; color: #333; text-align: center; margin-bottom: 2mm; }
.detail-info { font-size: 5.5pt; color: #444; line-height: 1.7; }
.detail-info .lbl { color: #888; }
.sosmed-area { position: absolute; bottom: 3mm; right: 2mm; width: 44mm; font-size: 5pt; color: #555; text-align: right; line-height: 1.8; z-index: 10; }
.sosmed-area .web { font-weight: bold; color: #1a3c3c; font-size: 5.5pt; }

/* TTD area */
.ttd-box { text-align: center; }
.ttd-box .garis { border-top: 1px solid #333; padding-top: 2px; font-size: 7pt; font-weight: bold; }
.ttd-box .jabatan { font-size: 6pt; color: #666; }
.qr-ttd { text-align: center; padding: 2px; border: 1px dashed #aaa; border-radius: 2px; margin-bottom: 2px; display: inline-block; }
.qr-ttd table.qr-html { border-collapse: collapse; margin: 0 auto 1px; }
.qr-ttd table.qr-html td { width: 2px; height: 2px; padding: 0; border: none; }
.qr-ttd .lbl { font-size: 5pt; color: #888; font-style: italic; }
</style>
</head>
<body>
<div class="wrapper">
    <div class="label-row">
        <div class="label-cell">SISI DEPAN</div>
        <div class="label-cell">SISI BELAKANG</div>
    </div>

    <div class="row-kartu">
        {{-- SISI DEPAN --}}
        <div class="col-kartu">
            <div class="kartu">
                <div class="bg-teal"></div>
                <div class="acc-gray"></div><div class="acc-brown"></div><div class="acc-yel"></div>
                <div class="logo-area">
                    @if(!empty($imgs['logo']))
                    <img src="{{ $imgs['logo'] }}" style="height:14mm;object-fit:contain;max-width:34mm;">
                    @endif
                    <div class="org-name">{{ $setting->nama_asosiasi ?? 'Asosiasi Dosen PAUD Indonesia' }}</div>
                    @if($setting && $setting->tagline)
                    <div class="tagline">{{ $setting->tagline }}</div>
                    @endif
                </div>
                <div class="qr-area">
                    {!! $qrDokumen !!}
                    <div class="exp" style="font-size:4pt;color:#555;">VERIFIKASI</div>
                    <div class="exp">EXP. {{ $memberDosen->tanggal_berakhir->format('dmY') }}</div>
                </div>
                <div class="website-badge">{{ $setting->website ?? 'www.asosiasi.or.id' }}</div>
            </div>
        </div>

        {{-- SISI BELAKANG --}}
        <div class="col-kartu">
            <div class="kartu">
                <div class="bg-teal-back"></div>
                <div class="acc-gray-l"></div><div class="acc-brown-l"></div><div class="acc-yel-l"></div>
                <div class="logo-back">
                    @if(!empty($imgs['logo']))
                    <img src="{{ $imgs['logo'] }}" style="width:14mm;height:14mm;object-fit:contain;">
                    @endif
                    <div class="singkatan">{{ $setting->singkatan ?? 'ADPAUD' }}</div>
                    <div class="label-kartu">KARTU ANGGOTA</div>
                </div>
                <div class="info-area">
                    <div class="nama-badge">{{ $memberDosen->dosen->nama }}</div>
                    <div class="no-anggota">Nomor Anggota: {{ $memberDosen->no_member }}</div>
                    <div class="detail-info">
                        <span class="lbl">NIDN &nbsp;&nbsp;&nbsp;:</span> {{ $memberDosen->dosen->nidn }}<br>
                        <span class="lbl">Prodi &nbsp;&nbsp;&nbsp;:</span> {{ $memberDosen->dosen->prodi->nama_prodi ?? '-' }}<br>
                        <span class="lbl">Jabatan :</span> {{ $memberDosen->dosen->jabatan_fungsional ?? '-' }}<br>
                        <span class="lbl">Berlaku &nbsp;:</span> s/d {{ $memberDosen->tanggal_berakhir->format('d/m/Y') }}
                    </div>
                </div>
                <div class="sosmed-area">
                    <div class="web">{{ $setting->website ?? '' }}</div>
                    @if($setting && $setting->email)<div>{{ $setting->email }}</div>@endif
                    @if($setting && $setting->instagram)<div>IG: {{ $setting->instagram }}</div>@endif
                    @if($setting && $setting->facebook)<div>FB: {{ $setting->facebook }}</div>@endif
                </div>
            </div>

            {{-- TTD HANYA 1 (KETUA UMUM) DI BAWAH KARTU, DIPERBESAR EXTREME & TUMPANG TINDIH --}}
            @php $modeTtd = $setting->mode_ttd ?? 'gambar'; @endphp
            <div style="width: 85.6mm; margin-top: 5mm; text-align: right;">
                <div class="ttd-box" style="display: inline-block; width: 50mm; text-align: center;">
                    
                    {{-- WADAH TTD & CAP OVERLAP DOMPDF --}}
                    <div style="height: 100px; text-align: center; margin-bottom: 5px;">
                        
                        {{-- 1. CAP DIMUNCULKAN DULU (Background), UKURAN JUMBO --}}
                        @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['cap']))
                        <img src="{{ $imgs['cap'] }}" style="height: 95px; opacity: 0.65; display: inline-block;">
                        @endif

                        {{-- 2. TTD DITARIK KE ATAS PAKAI MARGIN MINUS (Foreground), UKURAN JUMBO --}}
                        @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['ttd_ketua']))
                        <img src="{{ $imgs['ttd_ketua'] }}" style="height: 75px; display: block; margin: -80px auto 0 auto;">
                        @endif

                    </div>

                    @if(in_array($modeTtd, ['qr','keduanya']) && $qrKetua)
                    <div class="qr-ttd" style="margin-top: 5px;">{!! $qrKetua !!}<div class="lbl">TTD Digital Ketua</div></div>
                    @endif
                    
                    <div class="garis" style="margin-top: 5px;">{{ $setting->nama_ketua ?? 'Ketua Umum' }}</div>
                    <div class="jabatan">Ketua Umum</div>
                </div>
            </div>

        </div>
    </div>

</div>
</body>
</html>