<?php

namespace App\Console\Commands;

use App\Models\MemberDosen;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CekMemberExpired extends Command
{
    protected $signature = 'member:cek-expired';
    protected $description = 'Cek member yang akan expired dan kirim notifikasi email';

    public function handle()
    {
        // Update status expired
        MemberDosen::where('status', 'aktif')
            ->where('tanggal_berakhir', '<', Carbon::today())
            ->update(['status' => 'expired']);

        // Kirim notifikasi 30 hari sebelum expired
        $members = MemberDosen::where('status', 'aktif')
            ->where('notif_terkirim', false)
            ->whereBetween('tanggal_berakhir', [Carbon::today(), Carbon::today()->addDays(30)])
            ->with('dosen')
            ->get();

        foreach ($members as $member) {
            if ($member->dosen->email) {
                try {
                    Mail::send('emails.notif-expired', ['member' => $member], function ($m) use ($member) {
                        $m->to($member->dosen->email, $member->dosen->nama)
                          ->subject('Notifikasi: Masa Berlaku Member Akan Berakhir');
                    });
                    $member->update(['notif_terkirim' => true]);
                    $this->info("Notifikasi terkirim ke: {$member->dosen->email}");
                } catch (\Exception $e) {
                    $this->error("Gagal kirim ke {$member->dosen->email}: " . $e->getMessage());
                }
            }
        }

        $this->info('Selesai. Total notifikasi: ' . $members->count());
    }
}
