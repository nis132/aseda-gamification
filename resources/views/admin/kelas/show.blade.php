@extends('layouts.app')

@section('title', 'Detail Kelas')

@section('content')
<h3 class="mb-4">
    Siswa Kelas {{ $kelas->nama_kelas }}
</h3>

<div class="card shadow-sm">
    <div class="card-body">
        @if($siswa->count())
            <ul class="list-group">
                @foreach($siswa as $s)
                    <li class="list-group-item">
                        {{ $s->nama }}
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Belum ada siswa di kelas ini.</p>
        @endif
    </div>
</div>

<a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary mt-3">
    Kembali
</a>
@endsection
