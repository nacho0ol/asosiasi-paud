<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\MemberDosen;
use App\Models\MemberProdi;
use App\Models\Pembayaran;
use App\Models\Prodi;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DemoSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@paud.id',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        // Setting
        Setting::create([
            'nama_asosiasi'  => 'Asosiasi Dosen PAUD Indonesia',
            'singkatan'      => 'ADPAUD',
            'alamat'         => 'Jl. Pendidikan No. 1, Jakarta Pusat',
            'email'          => 'info@adpaud.or.id',
            'telepon'        => '021-12345678',
            'website'        => 'www.adpaud.or.id',
            'nama_ketua'     => 'Prof. Dr. Siti Rahayu, M.Pd.',
            'nama_bendahara' => 'Dr. Ahmad Fauzi, M.Pd.',
            'iuran_dosen'    => 300000,
            'iuran_prodi'    => 500000,
        ]);

        // Prodi
        $prodi1 = Prodi::create(['nama_prodi' => 'PG-PAUD', 'nama_universitas' => 'Universitas Negeri Jakarta',    'kota' => 'Jakarta',     'provinsi' => 'DKI Jakarta',       'email' => 'pgpaud@unj.ac.id',      'telepon' => '021-4700000',   'nama_kaprodi' => 'Dr. Nurhayati, M.Pd.']);
        $prodi2 = Prodi::create(['nama_prodi' => 'PG-PAUD', 'nama_universitas' => 'Universitas Pendidikan Indonesia', 'kota' => 'Bandung',  'provinsi' => 'Jawa Barat',        'email' => 'pgpaud@upi.edu',        'telepon' => '022-2013163',   'nama_kaprodi' => 'Prof. Dr. Heny Djoehaeni, M.Si.']);
        $prodi3 = Prodi::create(['nama_prodi' => 'PG-PAUD', 'nama_universitas' => 'Universitas Negeri Yogyakarta', 'kota' => 'Yogyakarta',  'provinsi' => 'DI Yogyakarta',     'email' => 'pgpaud@uny.ac.id',      'telepon' => '0274-586168',   'nama_kaprodi' => 'Dr. Enny Zubaidah, M.Pd.']);
        $prodi4 = Prodi::create(['nama_prodi' => 'PG-PAUD', 'nama_universitas' => 'Universitas Negeri Semarang',   'kota' => 'Semarang',    'provinsi' => 'Jawa Tengah',       'email' => 'pgpaud@unnes.ac.id',    'telepon' => '024-8508015',   'nama_kaprodi' => 'Dr. Lita Latiana, M.H.']);
        $prodi5 = Prodi::create(['nama_prodi' => 'PG-PAUD', 'nama_universitas' => 'Universitas Negeri Surabaya',   'kota' => 'Surabaya',    'provinsi' => 'Jawa Timur',        'email' => 'pgpaud@unesa.ac.id',    'telepon' => '031-8280009',   'nama_kaprodi' => 'Dr. Sri Setyowati, M.Pd.']);
        $prodi6 = Prodi::create(['nama_prodi' => 'PG-PAUD', 'nama_universitas' => 'Universitas Syiah Kuala',       'kota' => 'Banda Aceh',  'provinsi' => 'Aceh',              'email' => 'pgpaud@unsyiah.ac.id',  'telepon' => '0651-7551222',  'nama_kaprodi' => 'Dr. Fitriah, M.Pd.']);
        $prodi7 = Prodi::create(['nama_prodi' => 'PG-PAUD', 'nama_universitas' => 'Universitas Negeri Medan',      'kota' => 'Medan',       'provinsi' => 'Sumatera Utara',    'email' => 'pgpaud@unimed.ac.id',   'telepon' => '061-6625970',   'nama_kaprodi' => 'Dr. Kamtini, M.Pd.']);
        $prodi8 = Prodi::create(['nama_prodi' => 'PG-PAUD', 'nama_universitas' => 'Universitas Negeri Makassar',   'kota' => 'Makassar',    'provinsi' => 'Sulawesi Selatan',  'email' => 'pgpaud@unm.ac.id',      'telepon' => '0411-884457',   'nama_kaprodi' => 'Dr. Parwoto, M.Pd.']);

        // Dosen
        $dosens = [
            ['prodi_id' => $prodi1->id, 'nama' => 'Dr. Nurhayati, M.Pd.',            'nidn' => '0012345601', 'email' => 'nurhayati@unj.ac.id',    'jabatan_fungsional' => 'Lektor Kepala', 'pendidikan_terakhir' => 'S3'],
            ['prodi_id' => $prodi1->id, 'nama' => 'Dra. Siti Aisyah, M.Pd.',         'nidn' => '0012345602', 'email' => 'sitiaisyah@unj.ac.id',   'jabatan_fungsional' => 'Lektor',        'pendidikan_terakhir' => 'S2'],
            ['prodi_id' => $prodi1->id, 'nama' => 'Dr. Yuliani Nurani, M.Pd.',       'nidn' => '0012345603', 'email' => 'yuliani@unj.ac.id',      'jabatan_fungsional' => 'Guru Besar',    'pendidikan_terakhir' => 'S3'],
            ['prodi_id' => $prodi2->id, 'nama' => 'Prof. Dr. Heny Djoehaeni, M.Si.', 'nidn' => '0012345604', 'email' => 'heny@upi.edu',           'jabatan_fungsional' => 'Guru Besar',    'pendidikan_terakhir' => 'S3'],
            ['prodi_id' => $prodi2->id, 'nama' => 'Dr. Ocih Setiasih, M.Pd.',        'nidn' => '0012345605', 'email' => 'ocih@upi.edu',           'jabatan_fungsional' => 'Lektor Kepala', 'pendidikan_terakhir' => 'S3'],
            ['prodi_id' => $prodi3->id, 'nama' => 'Dr. Enny Zubaidah, M.Pd.',        'nidn' => '0012345606', 'email' => 'enny@uny.ac.id',         'jabatan_fungsional' => 'Lektor Kepala', 'pendidikan_terakhir' => 'S3'],
            ['prodi_id' => $prodi3->id, 'nama' => 'Drs. Bambang Sujiono, M.Pd.',     'nidn' => '0012345607', 'email' => 'bambang@uny.ac.id',      'jabatan_fungsional' => 'Lektor',        'pendidikan_terakhir' => 'S2'],
            ['prodi_id' => $prodi4->id, 'nama' => 'Dr. Lita Latiana, M.H.',          'nidn' => '0012345608', 'email' => 'lita@unnes.ac.id',       'jabatan_fungsional' => 'Lektor Kepala', 'pendidikan_terakhir' => 'S3'],
            ['prodi_id' => $prodi4->id, 'nama' => 'Dr. Eka Titi Andaryani, M.Pd.',   'nidn' => '0012345609', 'email' => 'eka@unnes.ac.id',        'jabatan_fungsional' => 'Lektor',        'pendidikan_terakhir' => 'S3'],
            ['prodi_id' => $prodi5->id, 'nama' => 'Dr. Sri Setyowati, M.Pd.',        'nidn' => '0012345610', 'email' => 'sri@unesa.ac.id',        'jabatan_fungsional' => 'Lektor Kepala', 'pendidikan_terakhir' => 'S3'],
            ['prodi_id' => $prodi5->id, 'nama' => 'Dr. Rachma Hasibuan, M.Kes.',     'nidn' => '0012345611', 'email' => 'rachma@unesa.ac.id',     'jabatan_fungsional' => 'Lektor',        'pendidikan_terakhir' => 'S3'],
            ['prodi_id' => $prodi6->id, 'nama' => 'Dr. Fitriah, M.Pd.',              'nidn' => '0012345612', 'email' => 'fitriah@unsyiah.ac.id',  'jabatan_fungsional' => 'Lektor',        'pendidikan_terakhir' => 'S3'],
            ['prodi_id' => $prodi7->id, 'nama' => 'Dr. Kamtini, M.Pd.',              'nidn' => '0012345613', 'email' => 'kamtini@unimed.ac.id',   'jabatan_fungsional' => 'Lektor Kepala', 'pendidikan_terakhir' => 'S3'],
            ['prodi_id' => $prodi7->id, 'nama' => 'Dra. Nasriah, M.Pd.',             'nidn' => '0012345614', 'email' => 'nasriah@unimed.ac.id',   'jabatan_fungsional' => 'Lektor',        'pendidikan_terakhir' => 'S2'],
            ['prodi_id' => $prodi8->id, 'nama' => 'Dr. Parwoto, M.Pd.',              'nidn' => '0012345615', 'email' => 'parwoto@unm.ac.id',      'jabatan_fungsional' => 'Lektor Kepala', 'pendidikan_terakhir' => 'S3'],
        ];

        foreach ($dosens as $i => $data) {
            $userDosen = User::create([
                'name'     => $data['nama'],
                'email'    => $data['email'],
                'password' => Hash::make('dosen123'),
                'role'     => 'dosen',
            ]);
            $dosen = Dosen::create(array_merge($data, [
                'user_id'            => $userDosen->id,
                'status_pendaftaran' => 'approved',
            ]));

            $mulai = Carbon::now()->subYear();
            $berakhir = ($i < 2) ? Carbon::now()->addDays(20) : $mulai->copy()->addYear();

            $member = MemberDosen::create([
                'dosen_id'         => $dosen->id,
                'no_member'        => 'MD-' . date('Y') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'tanggal_mulai'    => $mulai,
                'tanggal_berakhir' => $berakhir,
                'status'           => 'aktif',
            ]);

            Pembayaran::create([
                'no_kwitansi' => 'KWT-' . date('Ymd') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'jenis'       => 'dosen',
                'ref_id'      => $member->id,
                'jumlah'      => 300000,
                'tanggal_bayar' => $mulai,
                'keterangan'  => 'Iuran Keanggotaan Tahunan',
                'metode'      => $i % 2 === 0 ? 'tunai' : 'transfer',
            ]);
        }

        // Member Prodi
        $prodiList = [
            ['prodi' => $prodi1, 'email' => 'pgpaud.unj@paud.id'],
            ['prodi' => $prodi2, 'email' => 'pgpaud.upi@paud.id'],
            ['prodi' => $prodi3, 'email' => 'pgpaud.uny@paud.id'],
            ['prodi' => $prodi4, 'email' => 'pgpaud.unnes@paud.id'],
            ['prodi' => $prodi5, 'email' => 'pgpaud.unesa@paud.id'],
            ['prodi' => $prodi6, 'email' => 'pgpaud.unsyiah@paud.id'],
            ['prodi' => $prodi7, 'email' => 'pgpaud.unimed@paud.id'],
            ['prodi' => $prodi8, 'email' => 'pgpaud.unm@paud.id'],
        ];

        foreach ($prodiList as $i => $item) {
            $p = $item['prodi'];
            $userProdi = User::create([
                'name'     => $p->nama_kaprodi . ' (' . $p->nama_prodi . ')',
                'email'    => $item['email'],
                'password' => Hash::make('prodi123'),
                'role'     => 'prodi',
            ]);
            $p->update(['user_id' => $userProdi->id, 'status_pendaftaran' => 'approved']);

            $mulai = Carbon::now()->subMonths(6);
            $member = MemberProdi::create([
                'prodi_id'         => $p->id,
                'no_member'        => 'MP-' . date('Y') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'tanggal_mulai'    => $mulai,
                'tanggal_berakhir' => $mulai->copy()->addYear(),
                'status'           => 'aktif',
            ]);

            Pembayaran::create([
                'no_kwitansi' => 'KWT-' . date('Ymd') . '-P' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'jenis'       => 'prodi',
                'ref_id'      => $member->id,
                'jumlah'      => 500000,
                'tanggal_bayar' => $mulai,
                'keterangan'  => 'Iuran Keanggotaan Prodi',
                'metode'      => 'transfer',
            ]);
        }
    }
}
