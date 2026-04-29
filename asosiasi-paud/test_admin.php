<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$kernel = $app->make("Illuminate\Contracts\Http\Kernel");

// Login sebagai admin
$admin = App\Models\User::where("role","admin")->first();
if (!$admin) { echo "No admin user found\n"; exit; }
echo "Testing as admin: " . $admin->email . "\n\n";

function testAuth($label, $url, $kernel, $user) {
    try {
        $request = Illuminate\Http\Request::create($url, "GET");
        $request->setLaravelSession(app("session.store"));
        Auth::login($user);
        $request->cookies->set("XSRF-TOKEN", "test");
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        $ok = ($status === 200 || $status === 302);
        echo ($ok ? "[OK]" : "[ERR]") . " $label - HTTP $status\n";
        if ($status === 500) echo "     " . substr(strip_tags($response->getContent()), 0, 200) . "\n";
        $kernel->terminate($request, $response);
    } catch (Throwable $e) {
        echo "[ERR] $label: " . $e->getMessage() . " @ " . basename($e->getFile()) . ":" . $e->getLine() . "\n";
    }
}

use Illuminate\Support\Facades\Auth;

testAuth("Dashboard", "/", $kernel, $admin);
testAuth("Prodi index", "/prodi", $kernel, $admin);
testAuth("Dosen index", "/dosen", $kernel, $admin);
testAuth("Member Dosen index", "/member-dosen", $kernel, $admin);
testAuth("Member Prodi index", "/member-prodi", $kernel, $admin);
testAuth("Pembayaran index", "/pembayaran", $kernel, $admin);
testAuth("Setting", "/setting", $kernel, $admin);
testAuth("Tagihan", "/tagihan", $kernel, $admin);
testAuth("Pendaftaran", "/pendaftaran", $kernel, $admin);

// PDF routes
$md = App\Models\MemberDosen::first();
$mp = App\Models\MemberProdi::first();
$pb = App\Models\Pembayaran::first();
if ($md) testAuth("PDF Kartu Dosen", "/pdf/kartu-dosen/" . $md->id, $kernel, $admin);
if ($mp) testAuth("PDF Piagam Prodi", "/pdf/piagam-prodi/" . $mp->id, $kernel, $admin);
if ($pb) testAuth("PDF Kwitansi", "/pdf/kwitansi/" . $pb->id, $kernel, $admin);

echo "\nDone.\n";
