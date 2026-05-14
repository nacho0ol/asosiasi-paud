<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Times New Roman', serif; background: white; }
.piagam { width: 100%; min-height: 190mm; border: 8px double #8B6914; padding: 20px; text-align: center; }
.inner { border: 2px solid #8B6914; padding: 20px; min-height: 170mm; position: relative; }
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

/* Layout Footer & Barcode CSS */
.footer-area { margin-top: 40px; width: 100%; }
.barcode-col { float: left; width: 40%; text-align: left; padding-left: 20px; padding-top: 10px; }
.ttd-col { float: right; width: 40%; text-align: center; padding-right: 20px; }
.clearfix { clear: both; }

/* Styling khusus biar Barcode HTML mekar & kelihatan */
.qr-dokumen-box { display: inline-block; border: 2px solid #8B6914; padding: 6px; background: white; }
table.qr-html { border-collapse: collapse; margin: 0; }
table.qr-html td { width: 3.5px !important; height: 3.5px !important; padding: 0 !important; border: none !important; }
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

        <div class="footer-area">
            <div class="barcode-col">
                <div class="qr-dokumen-box">
                    {!! $qrDokumen !!}
                </div>
                <div style="font-size: 10px; color: #555; margin-top: 4px; font-weight: bold; margin-left: 2px;">
                    NO: {{ $memberProdi->no_member }}
                </div>
            </div>

            <div class="ttd-col">
                <div style="font-size: 12px; margin-bottom: 5px; font-weight: bold;">Ketua Umum</div>

                {{-- JURUS PAMUNGKAS DOMPDF: LAYERING POSITION ABSOLUTE --}}
                <div style="position: relative; height: 110px; margin-bottom: 5px;">
                    
                    {{-- LAYER 1: CAP (di belakang) --}}
                    @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['cap']))
                    <div style="position: absolute; top: 0; left: 0; width: 100%; text-align: center; z-index: 1;">
                        <img src="{{ $imgs['cap'] }}" style="height: 95px; opacity: 0.65;">
                    </div>
                    @endif

                    {{-- LAYER 2: TTD (di depan, agak diturunin dikit biar pas nebas tengah cap) --}}
                    @if(in_array($modeTtd, ['gambar','keduanya']) && !empty($imgs['ttd_ketua']))
                    <div style="position: absolute; top: 25px; left: 0; width: 100%; text-align: center; z-index: 2;">
                        <img src="{{ $imgs['ttd_ketua'] }}" style="height: 75px;">
                    </div>
                    @endif

                </div>

                <div style="border-top: 1px solid #333; font-weight: bold; font-size: 12px; padding-top: 3px; display: inline-block; min-width: 150px;">
                    {{ $setting->nama_ketua ?? 'Ketua Umum' }}
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

    </div>
</div>
</body>
</html>