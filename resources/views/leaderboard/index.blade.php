@extends('layouts.app')

@section('title', 'Leaderboard Kelas')

@section('content')
<div style="padding: 40px 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
    <div class="container">
        {{-- HEADER --}}
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h1 class="display-4 fw-bold text-white mb-3">
                    <i class="fas fa-trophy me-3"></i>
                    Leaderboard Kelas
                </h1>
                <p class="lead text-white-50 mb-0">
                    Papan peringkat berdasarkan total poin & waktu tercepat
                </p>
            </div>
        </div>

        {{-- LEADERBOARD CARD --}}
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="backdrop-filter: blur(20px); background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
                    
                    {{-- HEADER RANKING --}}
                    <div class="card-header bg-gradient text-white py-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h3 class="mb-0 fw-bold">
                                    <i class="fas fa-crown me-2"></i>
                                    Top {{ count($leaderboard) }} Siswa
                                </h3>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <span class="badge bg-light text-dark fs-6 px-3 py-2">
                                    Kelas {{ $kelas->kelas ?? 'Belum Terdaftar' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- BODY TABLE --}}
                    <div class="card-body p-0">
                        @if($leaderboard->count() > 0)
                        <div class="table-responsive" style="max-height: 600px;">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        <th class="border-0 text-center" style="width: 80px;">
                                            <i class="fas fa-hashtag"></i> Rank
                                        </th>
                                        <th class="border-0">
                                            <i class="fas fa-user me-2"></i>Nama Siswa
                                        </th>
                                        <th class="border-0 text-center" style="width: 150px;">
                                            <i class="fas fa-coins me-1"></i>Total Poin
                                        </th>
                                        <th class="border-0 text-center" style="width: 150px;">
                                            <i class="fas fa-stopwatch me-1"></i>Total Waktu
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    @foreach($leaderboard as $item)
                                    <tr class="hover-row">
                                        <td class="text-center">
                                            <div class="rank-badge">
                                                @if($loop->first)
                                                    <i class="fas fa-crown text-warning" style="font-size: 20px;"></i>
                                                @elseif($loop->iteration == 2)
                                                    <i class="fas fa-medal text-silver" style="font-size: 18px;"></i>
                                                @elseif($loop->iteration == 3)
                                                    <i class="fas fa-medal text-bronze" style="font-size: 18px;"></i>
                                                @else
                                                    <span class="fw-bold fs-5 text-white">{{ $item->rank }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-lg me-3 bg-gradient rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                    <i class="fas fa-user text-white fs-4"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-white">{{ $item->nama }}</h6>
                                                    <small class="text-white-50">ID: {{ $item->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="poin-badge">
                                                <span class="fs-4 fw-bold text-warning">{{ number_format($item->total_poin) }}</span>
                                                <small class="d-block text-white-50">XP</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-dark px-3 py-2 fs-6">
                                                {{ gmdate('H:i:s', $item->total_waktu) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-trophy fa-4x text-white-50 mb-4"></i>
                            <h4 class="text-white-50 mb-3">Belum ada data leaderboard</h4>
                            <p class="text-white-50 mb-4">Selesaikan tantangan untuk masuk peringkat!</p>
                            <a href="{{ route('tantangan.index') }}" class="btn btn-warning btn-lg">
                                <i class="fas fa-play me-2"></i>Mulai Tantangan
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rank-badge {
    min-height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.poin-badge {
    background: rgba(255,193,7,0.2);
    border: 1px solid rgba(255,193,7,0.3);
    border-radius: 12px;
    padding: 12px 20px;
    display: inline-block;
}

.hover-row:hover {
    background: rgba(255,255,255,0.1) !important;
    transform: scale(1.01);
}

.table-dark {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
}

.text-silver { color: #c0c0c0 !important; }
.text-bronze { color: #cd7f32 !important; }
</style>

@endsection
