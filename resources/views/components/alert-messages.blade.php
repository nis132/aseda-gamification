<div class="position-fixed top-0 start-0 p-3" style="z-index: 1060; width: 100%; max-width: 500px;">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-lg border-0" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fs-4 text-success"></i>
                <div>
                    <h6 class="mb-1 fw-bold">{{ session('message') ?? 'Berhasil!' }}</h6>
                    @if(session('nilai') || session('poin'))
                        <div class="small text-success-emphasis">
                            Nilai: <strong>{{ session('nilai', '-') }}</strong> 
                            | Poin: <strong>+{{ session('poin', 0) }}</strong>
                            @if(session('total_soal'))
                                ({{ session('total_soal') }} soal)
                            @endif
                        </div>
                    @endif
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-lg border-0" role="alert">
            <div class="d-flex">
                <i class="fas fa-exclamation-triangle me-3 fs-4 text-danger"></i>
                <strong>{{ session('error') }}</strong>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show shadow-lg border-0" role="alert">
            <div class="d-flex">
                <i class="fas fa-info-circle me-3 fs-4"></i>
                {{ session('info') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
</div>
