@extends('layouts.app')
@section('title', 'Buat Tantangan')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4>Buat Tantangan Baru</h4>
        </div>
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <form method="POST" action="{{ route('guru.tantangan.store') }}">
            @csrf
            <div class="card-body">

                <div class="mb-4">
                    <label>Judul</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
                </div>

                <div class="mb-4">
                    <label>Kelas</label>
                    <select name="kelas_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label>Total Poin</label>
                    <input type="number" name="poin" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label>Batas Waktu</label>
                    <input type="datetime-local" name="batas_waktu" class="form-control" required>
                </div>

            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success">
                    Simpan & Tambah Soal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection