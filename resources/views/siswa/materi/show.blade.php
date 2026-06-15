@extends('layouts.app')

@section('title', $materi->judul . ' — Materi Pelajaran')

@section('content')

{{-- FLOATING BUBBLE BACKGROUND --}}
<div class="bubble-bg" aria-hidden="true">
    <div class="bubble" style="width:380px;height:380px;top:-120px;left:-100px;animation-delay:0s;"></div>
    <div class="bubble" style="width:220px;height:220px;top:80px;left:300px;animation-delay:2s;"></div>
    <div class="bubble" style="width:160px;height:160px;top:20px;left:620px;animation-delay:4s;"></div>
    <div class="bubble" style="width:280px;height:280px;top:350px;left:-60px;animation-delay:1s;"></div>
    <div class="bubble" style="width:120px;height:120px;top:250px;left:480px;animation-delay:3s;"></div>
    <div class="bubble" style="width:350px;height:350px;top:600px;right:-80px;animation-delay:1.5s;"></div>
    <div class="bubble" style="width:180px;height:180px;top:700px;left:200px;animation-delay:2.5s;"></div>
    <div class="bubble" style="width:90px;height:90px;top:500px;left:700px;animation-delay:0.5s;"></div>
    <div class="bubble" style="width:240px;height:240px;top:1000px;left:-40px;animation-delay:3.5s;"></div>
    <div class="bubble" style="width:140px;height:140px;top:1100px;left:450px;animation-delay:1s;"></div>
    <div class="bubble" style="width:300px;height:300px;top:1400px;right:-60px;animation-delay:2s;"></div>
    <div class="bubble" style="width:100px;height:100px;top:1500px;left:150px;animation-delay:4s;"></div>
</div>

{{-- PAGE HEADER --}}
<div class="page-header mb-4">
    <div>
        <a href="{{ route('siswa.materi') }}" class="btn btn-light btn-action mb-2">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Materi
        </a>
        <h1 class="page-title">{{ $materi->judul }}</h1>
    </div>
    @if($sudahSelesai)
    <span class="badge rounded-pill px-3 py-2"
          style="background: #d1fae5; color: var(--clr-success); font-size: 0.8rem; align-self: flex-start; margin-top: 0.5rem;">
        <i class="fas fa-check-circle me-1"></i>Sudah Dipelajari
    </span>
    @endif
</div>

<div class="row g-4">
    {{-- KONTEN UTAMA --}}
    <div class="col-lg-8">

        {{-- META INFO --}}
        <div class="card mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap gap-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon stat-icon-primary"><i class="fas fa-graduation-cap"></i></div>
                        <div>
                            <div class="text-label">Kelas</div>
                            <div class="fw-semibold" style="font-size:0.875rem;color:var(--txt-primary);">{{ $materi->kelas->nama_kelas ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon stat-icon-info"><i class="fas fa-book"></i></div>
                        <div>
                            <div class="text-label">Mapel</div>
                            <div class="fw-semibold" style="font-size:0.875rem;color:var(--txt-primary);">{{ $materi->mapel->nama_mapel ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon stat-icon-warning"><i class="fas fa-user-tie"></i></div>
                        <div>
                            <div class="text-label">Guru</div>
                            <div class="fw-semibold" style="font-size:0.875rem;color:var(--txt-primary);">{{ $materi->guru->nama ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon stat-icon-primary" style="background:var(--clr-primary-light);color:var(--clr-primary);">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div>
                            <div class="text-label">Level</div>
                            <div class="fw-semibold" style="font-size:0.875rem;color:var(--txt-primary);">
                                Level {{ $materi->level_required }}+
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- VIDEO EMBED --}}
        @php $embedUrl = $materi->youtubeEmbedUrl(); @endphp
        @if($embedUrl)
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="fab fa-youtube text-danger fs-5"></i>
                <h6 class="fw-bold mb-0" style="color:var(--txt-primary);">Video Pembelajaran</h6>
            </div>
            <div class="card-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe src="{{ $embedUrl }}" title="Video Materi" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            style="border-radius:0 0 var(--border-radius-lg) var(--border-radius-lg);"></iframe>
                </div>
            </div>
        </div>
        @endif

        {{-- ISI MATERI --}}
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0" style="color:var(--txt-primary);">
                    <i class="fas fa-align-left me-2" style="color:var(--clr-primary);"></i>Isi Materi
                </h6>
                @if(!$sudahSelesai)
                <span id="scroll-hint" class="small" style="color:var(--txt-tertiary);">
                    <i class="fas fa-arrow-down me-1"></i>Scroll sampai selesai untuk menandai
                </span>
                @endif
            </div>

            @if(!$sudahSelesai)
            <div style="height:4px;background:var(--bg-muted);">
                <div id="read-progress" style="height:4px;width:0%;background:var(--clr-primary);transition:width 0.3s;"></div>
            </div>
            @endif

            <div class="card-body p-4 p-md-5" id="materi-content">

                <article class="materi-md">
                    {!! \App\Helpers\MarkdownHelper::render($materi->deskripsi) !!}
                </article>

                @if(!$sudahSelesai)
                <div class="text-center pt-4 mt-4" style="border-top:1px solid var(--border-color);">
                    <div id="belum-selesai-info">
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-2"
                             style="color:var(--txt-tertiary);font-size:0.85rem;">
                            <i class="fas fa-lock"></i>
                            <span>Baca materi sampai selesai untuk menandai selesai</span>
                        </div>
                        <div class="progress rounded-pill mx-auto" style="height:6px;max-width:200px;background:var(--bg-muted);">
                            <div id="selesai-progress-bar" class="progress-bar rounded-pill"
                                 style="width:0%;background:var(--clr-primary);transition:width 0.3s;"></div>
                        </div>
                    </div>
                    <div id="tombol-selesai" class="d-none">
                        <p style="font-size:0.85rem;color:var(--txt-secondary);margin-bottom:1rem;">
                            Kamu sudah membaca seluruh materi! Tandai sebagai selesai.
                        </p>
                        <form action="{{ route('siswa.materi.selesai', $materi->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="padding:0.65rem 2rem;font-size:0.95rem;">
                                <i class="fas fa-check-circle me-2"></i>Tandai Sudah Selesai
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="text-center p-4 mt-4 rounded-3" style="background:#d1fae5;border:1px solid #6ee7b7;">
                    <i class="fas fa-check-circle fa-2x mb-2" style="color:var(--clr-success);"></i>
                    <h6 class="fw-bold mb-1" style="color:var(--txt-primary);">Pelajaran Selesai!</h6>
                    <p style="font-size:0.82rem;color:var(--clr-success);margin:0;">Kamu telah menyelesaikan materi ini.</p>
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- SIDEBAR --}}
    <div class="col-lg-4">
        <div class="sticky-top" style="top:80px;">

            @if(!$sudahSelesai)
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="stat-icon stat-icon-primary" style="width:32px;height:32px;font-size:0.8rem;border-radius:8px;">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h6 class="mb-0 fw-bold" style="font-size:0.85rem;">Progress Membaca</h6>
                    </div>
                    <div class="progress rounded-pill mb-2" style="height:10px;background:var(--bg-muted);">
                        <div id="sidebar-progress" class="progress-bar rounded-pill"
                             style="width:0%;background:var(--clr-primary);transition:width 0.3s;"></div>
                    </div>
                    <div class="d-flex justify-content-between small" style="color:var(--txt-secondary);">
                        <span>0%</span>
                        <span id="progress-pct" class="fw-bold" style="color:var(--clr-primary);">0%</span>
                        <span>100%</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="fw-bold mb-0" style="color:var(--txt-primary);">
                        <i class="fas fa-paperclip me-2" style="color:var(--clr-primary);"></i>Lampiran & Referensi
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if($materi->file_url)
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="stat-icon stat-icon-danger"><i class="fas fa-file-pdf" style="font-size:1.2rem;"></i></div>
                        <div>
                            <div class="fw-semibold" style="font-size:0.875rem;color:var(--txt-primary);">Dokumen Materi</div>
                            <div style="font-size:0.75rem;color:var(--txt-secondary);">PDF / DOC</div>
                        </div>
                    </div>
                    <a href="{{ Storage::url($materi->file_url) }}" class="btn btn-primary w-100 mb-3" download>
                        <i class="fas fa-download me-2"></i>Download Dokumen
                    </a>
                    @endif

                    @if($materi->link_referensi)
                    <a href="{{ $materi->link_referensi }}" target="_blank" rel="noopener noreferrer"
                       class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none mb-2"
                       style="background:var(--bg-muted);border:1px solid var(--border-color);">
                        <div class="stat-icon stat-icon-info" style="width:36px;height:36px;font-size:0.85rem;border-radius:8px;flex-shrink:0;">
                            <i class="fas fa-external-link-alt"></i>
                        </div>
                        <div style="overflow:hidden;">
                            <div class="fw-semibold" style="font-size:0.82rem;color:var(--txt-primary);">Referensi Tambahan</div>
                            <div class="text-truncate" style="font-size:0.72rem;color:var(--txt-tertiary);">
                                {{ parse_url($materi->link_referensi, PHP_URL_HOST) ?? $materi->link_referensi }}
                            </div>
                        </div>
                        <i class="fas fa-chevron-right ms-auto" style="color:var(--txt-tertiary);font-size:0.75rem;"></i>
                    </a>
                    @endif

                    @if($materi->video_url)
                    <a href="{{ $materi->video_url }}" target="_blank" rel="noopener noreferrer"
                       class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none"
                       style="background:#fff5f5;border:1px solid #fecaca;">
                        <div style="width:36px;height:36px;background:#fee2e2;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1rem;">
                            <i class="fab fa-youtube text-danger"></i>
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:0.82rem;color:var(--txt-primary);">Tonton di YouTube</div>
                            <div style="font-size:0.72rem;color:#ef4444;">Buka di tab baru</div>
                        </div>
                        <i class="fas fa-chevron-right ms-auto" style="color:#ef4444;font-size:0.75rem;"></i>
                    </a>
                    @endif

                    @if(!$materi->file_url && !$materi->link_referensi && !$materi->video_url)
                    <div class="empty-state" style="padding:1.5rem 0;">
                        <div class="empty-state-icon" style="width:52px;height:52px;font-size:1.3rem;">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <p style="font-size:0.82rem;color:var(--txt-secondary);margin:0;">Tidak ada lampiran.</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-body p-2">
                    <a href="{{ route('siswa.materi') }}"
                       class="d-flex align-items-center gap-3 p-3 rounded-3 hover-lift"
                       style="text-decoration:none;border:1px solid transparent;transition:all var(--transition);"
                       onmouseover="this.style.borderColor='var(--border-color)';this.style.background='var(--bg-muted)'"
                       onmouseout="this.style.borderColor='transparent';this.style.background='transparent'">
                        <div class="stat-icon stat-icon-primary" style="width:36px;height:36px;">
                            <i class="fas fa-th-list" style="font-size:0.85rem;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:0.85rem;color:var(--txt-primary);">Daftar Materi</div>
                            <div style="font-size:0.72rem;color:var(--txt-secondary);">Kembali ke katalog materi</div>
                        </div>
                    </a>
                    @if(Route::has('siswa.tantangan'))
                    <a href="{{ route('siswa.tantangan') }}"
                       class="d-flex align-items-center gap-3 p-3 rounded-3 mt-1 hover-lift"
                       style="text-decoration:none;border:1px solid transparent;transition:all var(--transition);"
                       onmouseover="this.style.borderColor='var(--border-color)';this.style.background='var(--bg-muted)'"
                       onmouseout="this.style.borderColor='transparent';this.style.background='transparent'">
                        <div class="stat-icon stat-icon-warning" style="width:36px;height:36px;">
                            <i class="fas fa-trophy" style="font-size:0.85rem;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:0.85rem;color:var(--txt-primary);">Lihat Tantangan</div>
                            <div style="font-size:0.72rem;color:var(--txt-secondary);">Uji pemahamanmu sekarang</div>
                        </div>
                    </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* ── Bubble background melayang di belakang seluruh halaman ── */
.bubble-bg {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
}
.bubble {
    position: absolute;
    border-radius: 50%;
    background: radial-gradient(circle at 40% 40%, rgba(167,139,250,0.15), rgba(109,40,217,0.05));
    border: 1px solid rgba(167,139,250,0.18);
    animation: bubbleFloat 10s ease-in-out infinite alternate;
}
@keyframes bubbleFloat {
    0%   { transform: translateY(0px) scale(1); }
    100% { transform: translateY(-24px) scale(1.03); }
}

/* pastikan semua konten di atas bubble */
.page-header,
.row { position: relative; z-index: 1; }

/* ── Styling konten Markdown ── */
.materi-md { font-size: 1rem; line-height: 1.9; color: var(--txt-primary); }
.materi-md h1, .materi-md h2 {
    font-size: 1.15rem; font-weight: 700;
    color: var(--txt-primary);
    border-bottom: 2px solid var(--clr-primary-light);
    padding-bottom: 0.4rem;
    margin-top: 1.8rem; margin-bottom: 0.8rem;
}
.materi-md h3 {
    font-size: 1rem; font-weight: 700;
    color: var(--clr-primary);
    margin-top: 1.4rem; margin-bottom: 0.6rem;
}
.materi-md p { margin-bottom: 1rem; }
.materi-md strong { color: var(--txt-primary); font-weight: 700; }
.materi-md em { color: var(--clr-primary); }
.materi-md ul, .materi-md ol { padding-left: 1.5rem; margin-bottom: 1rem; }
.materi-md li { margin-bottom: 0.35rem; }
.materi-md blockquote {
    border-left: 4px solid var(--clr-primary);
    background: var(--clr-primary-light);
    padding: 0.75rem 1rem;
    border-radius: 0 8px 8px 0;
    margin: 1rem 0;
    font-style: italic;
    color: var(--txt-secondary);
}
.materi-md table { width: 100%; border-collapse: collapse; margin: 1rem 0; font-size: 0.875rem; }
.materi-md th {
    background: var(--clr-primary); color: #fff; font-weight: 700;
    padding: 0.6rem 0.8rem; text-align: left;
}
.materi-md td { padding: 0.5rem 0.8rem; border-bottom: 1px solid var(--border-color); }
.materi-md tr:nth-child(even) td { background: var(--bg-muted); }
.materi-md a { color: var(--clr-primary); text-decoration: underline; }
.materi-md a:hover { opacity: 0.8; }
.materi-md code {
    background: var(--bg-muted); padding: 0.15em 0.4em;
    border-radius: 4px; font-size: 0.875em; color: #e11d48;
}
.materi-md hr { border: none; border-top: 2px solid var(--border-color); margin: 1.5rem 0; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    @if(!$sudahSelesai)
    var content    = document.getElementById('materi-content');
    var infoEl     = document.getElementById('belum-selesai-info');
    var tombolEl   = document.getElementById('tombol-selesai');
    var hintEl     = document.getElementById('scroll-hint');
    var barTop     = document.getElementById('read-progress');
    var barSide    = document.getElementById('sidebar-progress');
    var barSelesai = document.getElementById('selesai-progress-bar');
    var pctEl      = document.getElementById('progress-pct');
    var unlocked   = false;

    function onScroll() {
        var rect     = content.getBoundingClientRect();
        var total    = content.offsetHeight;
        var scrolled = Math.max(0, window.innerHeight - rect.top);
        var pct      = Math.min(100, Math.round((scrolled / total) * 100));

        if (barTop)     barTop.style.width     = pct + '%';
        if (barSide)    barSide.style.width    = pct + '%';
        if (barSelesai) barSelesai.style.width = pct + '%';
        if (pctEl)      pctEl.textContent      = pct + '%';

        if (pct >= 90 && !unlocked) {
            unlocked = true;
            if (infoEl)   infoEl.classList.add('d-none');
            if (tombolEl) tombolEl.classList.remove('d-none');
            if (hintEl)   hintEl.classList.add('d-none');
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
    @endif
})();
</script>
@endpush