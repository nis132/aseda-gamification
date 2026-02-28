@extends('layouts.app')

@section('content')

<h3>Badge Saya</h3>

<div style="display:flex; gap:30px; flex-wrap:wrap;">

@forelse($badges as $badgeGroup)

    @php
        $badge = $badgeGroup->first()->badge;
        $jumlah = $badgeGroup->count();
    @endphp

    <div style="text-align:center;">
        <img src="{{ asset('storage/badge/'.$badge->icon) }}" 
             width="120">

        <h5>{{ $badge->nama_badge }}</h5>

        <p>x{{ $jumlah }}</p>
    </div>

@empty
    <p>Belum punya badge.</p>
@endforelse

</div>

@endsection
