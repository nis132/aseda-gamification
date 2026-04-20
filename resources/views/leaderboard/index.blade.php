@extends('layouts.app')

@section('title', 'Leaderboard Kelas')

@section('content')
<div style="min-height:100vh; background: linear-gradient(135deg,#667eea,#764ba2); padding: 25px 0;">

<div class="container">

    {{-- HEADER --}}
    <div class="text-center mb-4">
        <h2 class="fw-bold text-white mb-1">
            <i class="fas fa-trophy me-2"></i>Leaderboard Kelas
        </h2>
        <p class="text-white-50 mb-0">
            Poin & waktu tercepat menentukan peringkat
        </p>
    </div>

    {{-- CARD --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden"
         style="background: rgba(255,255,255,0.12); backdrop-filter: blur(15px);">

        {{-- HEADER CARD --}}
        <div class="card-header text-white py-3"
             style="background: linear-gradient(135deg,#f093fb,#f5576c);">

            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-crown me-2"></i>Top {{ $leaderboard->count() }} Siswa
                </h5>

                <span class="badge bg-light text-dark px-3 py-2">
                    Kelas {{ $kelas->nama_kelas ?? '-' }}
                </span>
            </div>

        </div>

        {{-- BODY --}}
        <div class="card-body p-3">

            @if($leaderboard->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" width="70">#</th>
                            <th>Nama</th>
                            <th class="text-center" width="140">Poin</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($leaderboard as $item)
                        <tr class="{{ auth()->id() == $item->id ? 'table-success' : '' }}">

                            {{-- RANK --}}
                            <td class="text-center fw-bold">
                                @if($loop->iteration == 1) 🥇
                                @elseif($loop->iteration == 2) 🥈
                                @elseif($loop->iteration == 3) 🥉
                                @else {{ $loop->iteration }}
                                @endif
                            </td>

                            {{-- NAMA --}}
                            <td class="fw-semibold">
                                {{ $item->nama }}
                                @if(auth()->id() == $item->id)
                                    <span class="badge bg-success ms-2">Kamu</span>
                                @endif
                            </td>

                            {{-- POIN --}}
                            <td class="text-center fw-bold text-warning">
                                {{ number_format($item->total_poin) }} XP
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            @else
                <div class="text-center text-white py-4">
                    <h5>Belum ada data leaderboard</h5>
                </div>
            @endif

        </div>
    </div>

</div>
</div>
@endsection