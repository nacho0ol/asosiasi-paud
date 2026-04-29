<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first() ?? new Setting();
        return view('setting.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_asosiasi' => 'required|string|max:255',
            'ttd_ketua'     => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'ttd_bendahara' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'cap'           => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'logo'          => 'nullable|mimes:jpg,jpeg,png|max:2048',
            'iuran_dosen'   => 'nullable|numeric|min:0',
            'iuran_prodi'   => 'nullable|numeric|min:0',
            'mode_ttd'      => 'nullable|in:gambar,qr,keduanya',
        ]);

        $setting = Setting::first() ?? new Setting();

        $data = $request->except(['ttd_ketua', 'ttd_bendahara', 'cap', 'logo', '_method', '_token']);

        // Generate kode TTD awal jika belum ada
        if (empty($setting->kode_ttd_ketua)) {
            $data['kode_ttd_ketua'] = strtoupper(\Illuminate\Support\Str::random(16));
            $data['ttd_ketua_at']   = now();
        }
        if (empty($setting->kode_ttd_bendahara)) {
            $data['kode_ttd_bendahara'] = strtoupper(\Illuminate\Support\Str::random(16));
            $data['ttd_bendahara_at']   = now();
        }
        if ($request->hasFile('ttd_ketua')) {
            $data['ttd_ketua']     = $request->file('ttd_ketua')->store('ttd', 'public');
            // Kode baru saat TTD diupload ulang
            $data['kode_ttd_ketua'] = strtoupper(\Illuminate\Support\Str::random(16));
            $data['ttd_ketua_at']   = now();
        }
        if ($request->hasFile('ttd_bendahara')) {
            $data['ttd_bendahara']     = $request->file('ttd_bendahara')->store('ttd', 'public');
            $data['kode_ttd_bendahara'] = strtoupper(\Illuminate\Support\Str::random(16));
            $data['ttd_bendahara_at']   = now();
        }
        if ($request->hasFile('cap')) {
            $data['cap'] = $request->file('cap')->store('ttd', 'public');
        }
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logo', 'public');
        }

        $setting->fill($data)->save();
        return redirect()->route('setting.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
