@extends('layouts.app')
@section('title', 'Leaderboard')

@section('content')
<div class="container py-5">
    <h2 class="mb-5 text-center">
        <i class="fas fa-trophy text-warning me-3"></i>
        Leaderboard Per Kelas
    </h2>
    
    @foreach($leaderboardByKelas as $kelasId => $items)
    <div class="card shadow-lg mb-5">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-chalkboard me-2"></i>
                Kelas {{ $kelasData[$kelasId]->nama_kelas ?? $kelasId }}
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th width="80">Rank</th>
                            <th width="60">Posisi</th>
                            <th>Nama Siswa</th>
                            <th>Total Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr class="{{ auth()->id() == $item->siswa_id ? 'table-success fw-bold' : '' }}">
                            <td>
                                <span class="badge bg-warning fs-6">
                                    #{{ $item->rank }}
                                </span>
                            </td>
                            <td>
                                @if($item->rank == 1) 🥇
                                @elseif($item->rank == 2) 🥈 
                                @elseif($item->rank == 3) 🥉
                                @else {{ $item->rank }}
                                @endif
                            </td>
                            <td>{{ $item->siswa->name ?? 'Siswa ' . $item->siswa_id }}</td>
                            <td class="text-success fw-bold">{{ number_format($item->total_poin) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
