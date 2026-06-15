<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat — {{ $badge->nama_badge }}</title>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Georgia', serif;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 1rem;
        }

        /* Action bar */
        .action-bar {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        .btn {
            padding: 0.6rem 1.4rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-back  { background: #e5e7eb; color: #374151; }
        .btn-print { background: #7c3aed; color: #fff; }
        .btn-dl    { background: #059669; color: #fff; }

        /* Kertas */
        .cert {
            width: 100%;
            max-width: 860px;
            background: #fff;
            border: 14px solid #7c3aed;
            border-radius: 16px;
            padding: 56px 72px;
            text-align: center;
            position: relative;
            box-shadow: 0 24px 64px rgba(124,58,237,0.15);
        }

        /* Ornamen sudut */
        .corner { position:absolute; width:52px; height:52px; opacity:0.15; }
        .corner svg { width:100%; height:100%; }
        .tl { top:12px;    left:12px; }
        .tr { top:12px;    right:12px;  transform:scaleX(-1); }
        .bl { bottom:12px; left:12px;   transform:scaleY(-1); }
        .br { bottom:12px; right:12px;  transform:scale(-1); }

        .eyebrow {
            font-size: 0.7rem;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: #7c3aed;
            font-weight: 700;
            font-family: sans-serif;
            margin-bottom: 0.4rem;
        }
        .heading {
            font-size: 2.6rem;
            font-weight: 700;
            color: #1e1b4b;
            margin-bottom: 0.2rem;
        }
        .subheading {
            font-size: 0.85rem;
            color: #9ca3af;
            font-family: sans-serif;
            margin-bottom: 2rem;
        }
        .divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 2rem;
        }
        .divider-line { width:60px; height:2px; background:linear-gradient(90deg,transparent,#7c3aed); border-radius:9999px; }
        .divider-line.r { background:linear-gradient(90deg,#7c3aed,transparent); }
        .divider-diamond { width:9px; height:9px; background:#7c3aed; transform:rotate(45deg); border-radius:2px; }

        .presented-to { font-size:0.875rem; color:#9ca3af; font-family:sans-serif; margin-bottom:0.3rem; }
        .recipient {
            font-size: 2.2rem;
            font-weight: 700;
            color: #7c3aed;
            border-bottom: 3px solid #ede9fe;
            display: inline-block;
            padding-bottom: 0.3rem;
            margin-bottom: 1.8rem;
        }
        .achievement-text {
            font-size: 0.95rem;
            color: #4b5563;
            font-family: sans-serif;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        /* Badge icon */
        .badge-icon {
            width: 96px; height: 96px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c3aed, #a78bfa);
            display: flex; align-items:center; justify-content:center;
            margin: 0 auto 1rem;
            font-size: 2.4rem; color: #fff;
            box-shadow: 0 10px 30px rgba(124,58,237,0.3);
        }
        .badge-title { font-size:1.25rem; font-weight:700; color:#1e1b4b; margin-bottom:0.25rem; }
        .badge-desc  { font-size:0.82rem; color:#9ca3af; font-family:sans-serif; margin-bottom:2.5rem; }

        /* Footer */
        .cert-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-top: 1.5rem;
            border-top: 1px solid #f3f4f6;
        }
        .meta-label { font-size:0.65rem; letter-spacing:0.15em; text-transform:uppercase; color:#d1d5db; font-family:sans-serif; margin-bottom:0.2rem; }
        .meta-value { font-size:0.875rem; font-weight:700; color:#374151; font-family:sans-serif; }
        .stamp {
            width:68px; height:68px; border-radius:50%;
            border:3px solid #7c3aed;
            display:flex; align-items:center; justify-content:center;
            color:#7c3aed; font-size:1.6rem; opacity:0.45;
        }

        @media print {
            body { background:#fff; padding:0; }
            .action-bar { display:none !important; }
            .cert { border-radius:0; box-shadow:none; padding:48px 64px; max-width:100%; }
        }
    </style>
</head>
<body>

<div class="action-bar">
    <a href="{{ url()->previous() }}" class="btn btn-back">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <button class="btn btn-print" onclick="window.print()">
        <i class="fas fa-print"></i> Cetak
    </button>
    <a href="{{ route('badge.sertifikat.download', $badge->id) }}" class="btn btn-dl">
        <i class="fas fa-file-pdf"></i> Download PDF
    </a>
</div>

<div class="cert">

    @php
        $svg = '<svg viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 48 L4 4 L48 4" stroke="#7c3aed" stroke-width="6" stroke-linecap="round" fill="none"/>
            <circle cx="4" cy="4" r="4" fill="#7c3aed"/>
        </svg>';
        $style = $badge->styleConfig();
    @endphp
    <div class="corner tl">{!! $svg !!}</div>
    <div class="corner tr">{!! $svg !!}</div>
    <div class="corner bl">{!! $svg !!}</div>
    <div class="corner br">{!! $svg !!}</div>

    <div class="eyebrow">Sertifikat Pencapaian</div>
    <div class="heading">Certificate of Achievement</div>
    <div class="subheading">Dengan bangga diberikan kepada</div>

    <div class="divider">
        <div class="divider-line"></div>
        <div class="divider-diamond"></div>
        <div class="divider-line r"></div>
    </div>

    <div class="presented-to">Nama Siswa</div>
    <div class="recipient">{{ $siswa->nama }}</div>

    <div class="achievement-text">
        telah berhasil menyelesaikan seluruh tantangan pada satu mata pelajaran<br>
        dan meraih penghargaan tertinggi sebagai
    </div>

    <div class="badge-icon">
        <i class="fas {{ $style['icon'] }}"></i>
    </div>
    <div class="badge-title">{{ $badge->nama_badge }}</div>
    <div class="badge-desc">{{ $badge->deskripsi }}</div>

    <div class="cert-footer">
        <div>
            <div class="meta-label">NIS</div>
            <div class="meta-value">{{ $siswa->nis ?? '—' }}</div>
        </div>
        <div class="stamp"><i class="fas fa-award"></i></div>
        <div style="text-align:right;">
            <div class="meta-label">Tanggal Diraih</div>
            <div class="meta-value">
                {{ \Carbon\Carbon::parse($siswaBadge->diterima_pada)->translatedFormat('d F Y') }}
            </div>
        </div>
    </div>

</div>
</body>
</html>