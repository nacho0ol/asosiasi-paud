<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="font-family:Arial,sans-serif;color:#333;max-width:600px;margin:0 auto;padding:20px">
    <div style="background:#1a3c5e;color:white;padding:20px;border-radius:8px 8px 0 0;text-align:center">
        <h2 style="margin:0">Notifikasi Keanggotaan</h2>
        <p style="margin:5px 0;font-size:13px">Asosiasi Dosen PAUD Indonesia</p>
    </div>
    <div style="border:1px solid #ddd;border-top:none;padding:25px;border-radius:0 0 8px 8px">
        <p>Yth. <strong>{{ $member->dosen->nama }}</strong>,</p>
        <p style="margin-top:15px">Kami menginformasikan bahwa masa berlaku keanggotaan Anda akan segera berakhir:</p>
        <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:6px;padding:15px;margin:15px 0">
            <table style="width:100%;font-size:13px">
                <tr><td style="color:#666;width:40%">No Member</td><td><strong>{{ $member->no_member }}</strong></td></tr>
                <tr><td style="color:#666">Berlaku Hingga</td><td><strong style="color:#dc3545">{{ $member->tanggal_berakhir->format('d F Y') }}</strong></td></tr>
                <tr><td style="color:#666">Sisa Hari</td><td><strong>{{ now()->diffInDays($member->tanggal_berakhir) }} hari</strong></td></tr>
            </table>
        </div>
        <p>Segera lakukan perpanjangan keanggotaan agar Anda tetap dapat menikmati seluruh fasilitas dan hak sebagai anggota.</p>
        <p style="margin-top:20px;font-size:12px;color:#666">
            Untuk informasi lebih lanjut, silakan hubungi sekretariat asosiasi.<br>
            Email ini dikirim otomatis oleh sistem.
        </p>
    </div>
</body>
</html>
