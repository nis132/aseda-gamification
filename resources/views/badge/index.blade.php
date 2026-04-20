@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    .badge-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 2rem;
        padding: 20px;
    }
    .badge-item {
        background: #fff;
        border-radius: 20px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .badge-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .badge-icon {
        width: 100px;
        height: 100px;
        object-fit: contain;
        margin-bottom: 15px;
    }
    .count-tag {
        background: #6c5ce7;
        color: white;
        padding: 2px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
    }
    /* Overlay Penyelamat (Popup Animasi) */
    .sring-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.85);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }
    .sring-content h1 {
        color: #ffd700;
        font-family: 'Arial Black', sans-serif;
        font-size: 3rem;
        text-shadow: 0 0 20px rgba(255, 215, 0, 0.6);
    }
</style>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Koleksi Badge Saya</h3>
        <p class="text-muted">Total Badge: {{ $ownedBadges->count() }} Jenis</p>
    </div>

    <div class="badge-container">
        @forelse($ownedBadges as $badgeId => $group)
            @php 
                $badge = $group->first()->badge; 
                $isNew = $group->contains('is_new', true);
            @endphp
            
            <div class="badge-item animate__animated animate__fadeInUp">
                <img src="{{ asset('storage/badge/'.$badge->icon) }}" class="badge-icon">
                <h5 class="fw-bold mb-1">{{ $badge->nama_badge }}</h5>
                <span class="count-tag">x{{ $group->count() }}</span>
            </div>

            {{-- POPUP ANIMASI JIKA ADA BADGE BARU --}}
            @if($isNew)
                <div id="overlay-{{ $badgeId }}" class="sring-overlay">
                    <div class="sring-content text-center animate__animated animate__jackInTheBox">
                        <h1 class="mb-0">SELAMAT!</h1>
                        <p class="text-white fs-4 mb-4">Kamu baru saja meraih badge baru</p>
                        <img src="{{ asset('storage/badge/'.$badge->icon) }}" width="250" class="mb-4">
                        <h2 class="text-white mb-4">{{ $badge->nama_badge }}</h2>
                        <button onclick="closeOverlay('{{ $badgeId }}')" class="btn btn-lg btn-warning px-5 fw-bold rounded-pill">MANTAP!</button>
                    </div>
                </div>
                
                @php 
                    // Update is_new ke false lewat DB agar tidak muncul lagi
                    \App\Models\SiswaBadge::where('siswa_id', auth()->id())
                        ->where('badge_id', $badgeId)
                        ->update(['is_new' => false]);
                @endphp
            @endif

        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Kamu belum memiliki badge. Ayo selesaikan tantangan!</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    function closeOverlay(id) {
        const el = document.getElementById('overlay-' + id);
        el.classList.add('animate__animated', 'animate__fadeOut');
        setTimeout(() => {
            el.remove();
        }, 500);
    }
</script>
@endsection