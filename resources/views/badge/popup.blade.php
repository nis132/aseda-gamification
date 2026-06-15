{{-- OVERLAY BADGE --}}
@foreach($newBadges as $badgeItem)
<div id="badgePopup-{{ $badgeItem->id }}" class="badge-overlay-global">
    <div class="badge-content-global animate__animated animate__zoomIn text-center">

        <h1 class="text-gold mb-2">LUAR BIASA!</h1>
        <p class="mb-3">Kamu mendapatkan badge baru</p>

        <img 
            src="{{ asset('storage/badge/' . ($badgeItem->badge->icon ?? 'default.png')) }}" 
            class="badge-img-new mb-3">

        <h3 class="fw-bold mb-4">
            {{ $badgeItem->badge->nama_badge ?? 'Badge Baru' }}
        </h3>

        <button 
            onclick="closeBadgePopup({{ $badgeItem->id }})"
            class="btn btn-warning px-4 py-2 rounded-pill fw-bold shadow">
            Ambil Hadiah
        </button>

    </div>
</div>
@endforeach

{{-- STYLE --}}
<style>
.badge-overlay-global {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.85);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(6px);
}

.badge-content-global {
    color: white;
    max-width: 400px;
}

.badge-img-new {
    width: 150px;
    height: 150px;
    object-fit: contain;
    filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.6));
}

.text-gold {
    color: #ffd700;
    font-weight: 800;
}
</style>

{{-- SCRIPT --}}
<script>
function closeBadgePopup(id) {

    fetch(`/siswa/badge/mark-as-seen/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content')
        }
    });

    const popup = document.getElementById('badgePopup-' + id);

    if (popup) {
        popup.classList.add('animate__animated', 'animate__fadeOut');

        setTimeout(() => {
            popup.remove();
        }, 500);
    }
}
</script>