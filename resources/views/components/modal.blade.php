{{--
    resources/views/components/modal.blade.php

    Props:
    - id       : string  — ID unik modal (wajib)
    - title    : string  — Judul modal
    - type     : string  — 'primary' | 'success' | 'warning' | 'danger' | 'info'
    - icon     : string  — Class FontAwesome tanpa 'fas', misal 'fa-trash'
    - size     : string  — '' | 'sm' | 'lg' | 'xl' (opsional)
--}}
@props([
    'id',
    'title'  => '',
    'type'   => 'primary',
    'icon'   => '',
    'size'   => '',
])

@php
$iconBg = match($type) {
    'success' => 'bg-success-subtle text-success',
    'warning' => 'bg-warning-subtle text-warning',
    'danger'  => 'bg-danger-subtle text-danger',
    'info'    => 'bg-info-subtle text-info',
    default   => 'bg-primary-subtle text-primary',
};
@endphp

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered {{ $size ? 'modal-'.$size : '' }}">
        <div class="modal-content border-0">

            {{-- Header --}}
            <div class="modal-header">
                <div class="d-flex align-items-center gap-2">
                    @if($icon)
                        <div class="{{ $iconBg }} d-flex align-items-center justify-content-center rounded-2"
                             style="width: 30px; height: 30px; font-size: 0.85rem; flex-shrink:0;">
                            <i class="fas {{ $icon }}"></i>
                        </div>
                    @endif
                    <h6 class="modal-title fw-bold mb-0" id="{{ $id }}-label">{{ $title }}</h6>
                </div>
                {{-- FIX: data-bs-dismiss="modal" (bukan "alert") --}}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body py-4 px-4">
                {{ $slot }}
            </div>

            {{-- Footer (opsional) --}}
            @isset($footer)
                <div class="modal-footer border-top py-3 px-4">
                    {{ $footer }}
                </div>
            @endisset

        </div>
    </div>
</div>