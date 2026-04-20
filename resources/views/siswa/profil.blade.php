@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="row g-3">

    {{-- LEFT --}}
    <div class="col-lg-8">

        {{-- PROFILE HEADER --}}
        <div class="card shadow-sm border-0 rounded-4 mb-3">
            <div class="card-body p-3 d-flex align-items-center">

                <div class="me-3">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                         style="width:60px;height:60px;">
                        <i class="fas fa-user text-primary"></i>
                    </div>
                </div>

                <div>
                    <h5 class="mb-0 fw-bold">{{ $user->name }}</h5>
                    <small class="text-muted">
                        Kelas {{ $kelas->nama_kelas ?? '-' }}
                    </small>
                </div>

                <div class="ms-auto text-end">
                    <span class="badge bg-warning text-dark px-3 py-2">
                        Level {{ $level }}
                    </span>
                </div>

            </div>
        </div>

        {{-- STATS --}}
        <div class="row g-3 mb-3">

            <div class="col-md-6">
                <div class="card border-0 bg-light p-3 text-center rounded-4">
                    <small class="text-muted">Tantangan Selesai</small>
                    <h4 class="fw-bold mb-0">{{ $tantanganSelesai }}</h4>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 bg-warning text-white p-3 text-center rounded-4">
                    <small>Total XP</small>
                    <h4 class="fw-bold mb-0">{{ number_format($totalPoin) }}</h4>
                </div>
            </div>

        </div>

        {{-- LEVEL PROGRESS --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-3">Progress Level</h6>

                <div class="d-flex justify-content-between small mb-1">
                    <span>Level {{ $level }}</span>
                    <span>Max 5</span>
                </div>

                <div class="progress" style="height:6px;">
                    <div class="progress-bar bg-success"
                         style="width: {{ ($level/5)*100 }}%">
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-lg-4">

        {{-- RANK --}}
        <div class="card shadow-sm border-0 rounded-4 mb-3 text-center p-3">
            <small class="text-muted">Peringkat Kelas</small>

            @if($rank)
                <h2 class="fw-bold mb-0">#{{ $rank }}</h2>
                <small class="text-muted">
                    Kelas {{ $kelas->nama_kelas ?? '-' }}
                </small>
            @else
                <small class="text-muted">Belum ada peringkat</small>
            @endif
        </div>

        {{-- BADGES --}}
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-3">
                <h6 class="fw-bold mb-3">Badge</h6>

                <div class="row g-2">
                    @forelse($badges as $badgeId => $group)
                        @php $badge = $group->first()->badge; @endphp

                        <div class="col-4 text-center">
                            <div class="p-2 bg-light rounded-3">

                                <img src="{{ asset('storage/badge/' . $badge->icon) }}"
                                     style="width:40px;height:40px;object-fit:contain;">

                                <div class="small fw-bold mt-1">
                                    {{ Str::limit($badge->nama_badge, 10) }}
                                </div>

                                <span class="badge bg-primary small">
                                    x{{ $group->count() }}
                                </span>

                            </div>
                        </div>

                    @empty
                        <div class="col-12 text-center">
                            <small class="text-muted">Belum ada badge</small>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

    </div>
</div>
@endsection