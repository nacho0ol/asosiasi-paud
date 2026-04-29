@extends('layouts.app')
@section('title', 'Catat Pembayaran')
@section('content')
<div class="card" style="max-width:550px">
    <div class="card-header"><i class="bi bi-receipt"></i> Catat Pembayaran</div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form action="{{ route('pembayaran.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Jenis Member <span class="text-danger">*</span></label>
                <select name="jenis" id="jenis" class="form-select" required onchange="toggleMember()">
                    <option value="">-- Pilih --</option>
                    <option value="dosen" {{ old('jenis') === 'dosen' ? 'selected' : '' }}>Member Dosen</option>
                    <option value="prodi" {{ old('jenis') === 'prodi' ? 'selected' : '' }}>Member Prodi</option>
                </select>
            </div>
            <div class="mb-3" id="div-dosen" style="display:none">
                <label class="form-label">Pilih Member Dosen</label>
                <select name="ref_id" id="ref_dosen" class="form-select">
                    <option value="">-- Pilih --</option>
                    @foreach($memberDosens as $md)
                    <option value="{{ $md->id }}" {{ old('ref_id') == $md->id ? 'selected' : '' }}>
                        {{ $md->no_member }} - {{ $md->dosen->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3" id="div-prodi" style="display:none">
                <label class="form-label">Pilih Member Prodi</label>
                <select name="ref_id" id="ref_prodi" class="form-select">
                    <option value="">-- Pilih --</option>
                    @foreach($memberProdis as $mp)
                    <option value="{{ $mp->id }}" {{ old('ref_id') == $mp->id ? 'selected' : '' }}>
                        {{ $mp->no_member }} - {{ $mp->prodi->nama_prodi }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', $setting->iuran_dosen ?? 0) }}" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" class="form-control" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Metode</label>
                    <select name="metode" class="form-select">
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}" placeholder="Iuran tahunan, dll">
            </div>
            <button class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
function toggleMember() {
    var jenis = document.getElementById('jenis').value;
    document.getElementById('div-dosen').style.display = jenis === 'dosen' ? 'block' : 'none';
    document.getElementById('div-prodi').style.display = jenis === 'prodi' ? 'block' : 'none';
}
document.addEventListener('DOMContentLoaded', toggleMember);
</script>
@endsection
