@extends('layouts.app')
@section('title', 'Edit Materi')

@section('content')
<div class="container mt-4">
    <div class="card shadow border-0">
        <div class="card-body">

            <h4 class="mb-4">Edit Materi</h4>

            <form action="{{ route('guru.materi.update', $materi) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label>Judul</label>
                    <input type="text" name="judul" class="form-control" value="{{ $materi->judul }}" required>
                </div>

                <div class="mb-3">
                    <label>Kelas</label>
                    <select name="kelas_id" class="form-control" required>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ $materi->kelas_id == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Mapel</label>
                    <select name="mapel_id" class="form-control" required>
                        @foreach($mapel as $m)
                            <option value="{{ $m->id }}" {{ $materi->mapel_id == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4">{{ $materi->deskripsi }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Ganti File (opsional)</label>
                    <input type="file" name="file_materi" class="form-control">
                </div>

                <button class="btn btn-primary">Update</button>
                <a href="{{ route('guru.materi') }}" class="btn btn-secondary">Batal</a>

            </form>

        </div>
    </div>
</div>
@endsection