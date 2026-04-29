<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$kernel = $app->make("Illuminate\Contracts\Http\Kernel");

function testPage($label, $url, $kernel, $expectedStatus = 200) {
    try {
        $request = Illuminate\Http\Request::create($url, "GET");
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        $ok = ($status === $expectedStatus || $status === 302 || $status === 200);
        echo ($ok ? "[OK]" : "[ERR]") . " $label - HTTP $status\n";
        if (!$ok) echo "     " . substr($response->getContent(), 0, 150) . "\n";
        $kernel->terminate($request, $response);
    } catch (Throwable $e) {
        echo "[ERR] $label: " . $e->getMessage() . " @ " . basename($e->getFile()) . ":" . $e->getLine() . "\n";
    }
}

testPage("Login", "/login", $kernel);
testPage("Register dosen", "/daftar-anggota", $kernel);

$p = App\Models\Pembayaran::whereNotNull("kode_verifikasi")->first();
if ($p) testPage("Verifikasi kwitansi", "/verifikasi/kwitansi/" . $p->kode_verifikasi, $kernel);

$md = App\Models\MemberDosen::whereNotNull("kode_verifikasi")->first();
if ($md) testPage("Verifikasi kartu", "/verifikasi/kartu/" . $md->kode_verifikasi, $kernel);

$mp = App\Models\MemberProdi::whereNotNull("kode_verifikasi")->first();
if ($mp) testPage("Verifikasi piagam", "/verifikasi/piagam/" . $mp->kode_verifikasi, $kernel);

testPage("Verifikasi tidak ditemukan", "/verifikasi/kwitansi/TIDAKADA999", $kernel);
testPage("Dashboard redirect", "/", $kernel, 302);
testPage("Prodi redirect", "/prodi", $kernel, 302);
testPage("Dosen redirect", "/dosen", $kernel, 302);
testPage("Member dosen redirect", "/member-dosen", $kernel, 302);
testPage("Member prodi redirect", "/member-prodi", $kernel, 302);
testPage("Pembayaran redirect", "/pembayaran", $kernel, 302);
testPage("Setting redirect", "/setting", $kernel, 302);
testPage("Tagihan redirect", "/tagihan", $kernel, 302);
testPage("Pendaftaran redirect", "/pendaftaran", $kernel, 302);
testPage("Portal redirect", "/portal", $kernel, 302);
testPage("Portal prodi redirect", "/portal/prodi", $kernel, 302);
echo "\nDone.\n";
