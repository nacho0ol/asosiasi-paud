<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Asosiasi</title>
    <style>
        body { font-family: 'Arial', sans-serif; background: #f0f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center; width: 100%; max-width: 400px; }
        .logo { width: 80px; margin-bottom: 15px; }
        .title { font-size: 18px; color: #555; margin-bottom: 5px; }
        .tagihan { font-size: 14px; color: #888; margin-bottom: 15px; }
        .price { font-size: 32px; font-weight: bold; color: #2ab5a5; margin-bottom: 20px; }
        .btn-bayar { background: #f0a500; color: white; border: none; padding: 14px 20px; font-size: 16px; font-weight: bold; border-radius: 8px; cursor: pointer; width: 100%; transition: 0.3s; }
        .btn-bayar:hover { background: #d69300; }
        .note { font-size: 12px; color: #aaa; margin-top: 15px; }
    </style>
</head>
<body>

    <div class="card">
        <div class="title">Selesaikan Pembayaran Anda</div>
        <div class="tagihan">No. Tagihan: <strong>{{ $tagihan->no_tagihan }}</strong></div>
        
        <div class="price">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</div>
        
        <button id="pay-button" class="btn-bayar">Bayar via Midtrans</button>
        
        <div class="note">Mendukung GoPay, QRIS, ShopeePay, & Transfer Bank</div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // Memanggil pop-up Midtrans pakai Snap Token dari Controller
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    alert("Pembayaran Berhasil! Status member Anda sudah Aktif.");
                    // Balikin ke halaman depan/dashboard setelah bayar
                    window.location.href = '/'; 
                },
                onPending: function(result){
                    alert("Menunggu pembayaranmu!");
                },
                onError: function(result){
                    alert("Pembayaran Gagal!");
                },
                onClose: function(){
                    alert('Kamu menutup layar sebelum menyelesaikan pembayaran.');
                }
            });
        };
    </script>

</body>
</html>