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
                    Peringkat berdasarkan total poin & waktu tercepat
                </p>
            </div>
        </div>

        {{-- CARD --}}
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden"
                     style="backdrop-filter: blur(20px); background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">

                    {{-- HEADER --}}
                    <div class="card-header text-white py-4"
                         style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h3 class="mb-0 fw-bold">
                                    <i class="fas fa-crown me-2"></i>
                                    Top {{ $leaderboard->count() }} Siswa
                                </h3>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <span class="badge bg-light text-dark fs-6 px-3 py-2">
                                    Kelas {{ $kelas->nama_kelas ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- TABLE --}}
                    <div class="card-body p-0">

                        @if($leaderboard->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" width="80">Rank</th>
                                        <th>Nama</th>
                                        <th class="text-center" width="150">Total Poin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaderboard as $item)
                                    <tr class="{{ auth()->id() == $item->id ? 'table-success' : '' }}">

                                        {{-- RANK --}}
                                        <td class="text-center fw-bold">
                                            @if($loop->iteration == 1)
                                                🥇
                                            @elseif($loop->iteration == 2)
                                                🥈
                                            @elseif($loop->iteration == 3)
                                                🥉
                                            @else
                                                {{ $loop->iteration }}
                                            @endif
                                        </td>

                                        {{-- NAMA --}}
                                        <td>
                                            {{ $item->nama }}
                                            @if(auth()->id() == $item->id)
                                                <span class="badge bg-success ms-2">Kamu</span>
                                            @endif
                                        </td>

                                        {{-- TOTAL POIN --}}
                                        <td class="text-center fw-bold text-warning">
                                            {{ number_format($item->total_poin) }} XP
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-5 text-white">
                            <h4>Belum ada data leaderboard</h4>
                            <p>Selesaikan tantangan untuk masuk peringkat.</p>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection