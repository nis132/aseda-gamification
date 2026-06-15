<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DejaVu Serif', Georgia, serif; background:#fff; }

        .cert {
            width: 100%;
            border: 12px solid #7c3aed;
            padding: 50px 70px;
            text-align: center;
        }
        .eyebrow {
            font-size: 8pt;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #7c3aed;
            font-weight: bold;
            font-family: 'DejaVu Sans', sans-serif;
            margin-bottom: 4px;
        }
        .heading {
            font-size: 28pt;
            font-weight: bold;
            color: #1e1b4b;
            margin-bottom: 4px;
        }
        .subheading {
            font-size: 9pt;
            color: #9ca3af;
            font-family: 'DejaVu Sans', sans-serif;
            margin-bottom: 20px;
        }
        hr { border:none; border-top:2px solid #ede9fe; width:100px; margin: 0 auto 20px; }
        .presented-to { font-size:9pt; color:#9ca3af; font-family:'DejaVu Sans',sans-serif; margin-bottom:3px; }
        .recipient {
            font-size: 24pt;
            font-weight: bold;
            color: #7c3aed;
            border-bottom: 3px solid #ede9fe;
            display: inline-block;
            padding-bottom: 3px;
            margin-bottom: 16px;
        }
        .achievement-text {
            font-size: 10pt;
            color: #4b5563;
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.8;
            margin-bottom: 20px;
        }
        .badge-box {
            background: #ede9fe;
            border-radius: 10px;
            padding: 16px 40px;
            display: inline-block;
            margin-bottom: 32px;
        }
        .badge-title { font-size:15pt; font-weight:bold; color:#5b21b6; margin-bottom:3px; }
        .badge-desc  { font-size:9pt; color:#7c3aed; font-family:'DejaVu Sans',sans-serif; }

        .footer { border-top:1px solid #f3f4f6; padding-top:16px; margin-top:8px; }
        .footer table { width:100%; }
        .meta-label { font-size:7pt; letter-spacing:2px; text-transform:uppercase; color:#d1d5db; font-family:'DejaVu Sans',sans-serif; margin-bottom:3px; }
        .meta-value { font-size:10pt; font-weight:bold; color:#374151; font-family:'DejaVu Sans',sans-serif; }
        .stamp { font-size:20pt; color:#7c3aed; opacity:0.4; }
    </style>
</head>
<body>
<div class="cert">

    <div class="eyebrow">Sertifikat Pencapaian</div>
    <div class="heading">Certificate of Achievement</div>
    <div class="subheading">Dengan bangga diberikan kepada</div>
    <hr>

    <div class="presented-to">Nama Siswa</div>
    <div class="recipient">{{ $siswa->nama }}</div>

    <div class="achievement-text">
        telah berhasil menyelesaikan seluruh tantangan pada satu mata pelajaran<br>
        dan meraih penghargaan tertinggi sebagai
    </div>

    <div class="badge-box">
        <div class="badge-title">{{ $badge->nama_badge }}</div>
        <div class="badge-desc">{{ $badge->deskripsi }}</div>
    </div>

    <div class="footer">
        <table>
            <tr>
                <td style="text-align:left; width:33%;">
                    <div class="meta-label">NIS</div>
                    <div class="meta-value">{{ $siswa->nis ?? '—' }}</div>
                </td>
                <td style="text-align:center; width:34%;">
                    <div class="stamp">&#9733;</div>
                </td>
                <td style="text-align:right; width:33%;">
                    <div class="meta-label">Tanggal Diraih</div>
                    <div class="meta-value">
                        {{ \Carbon\Carbon::parse($siswaBadge->diterima_pada)->translatedFormat('d F Y') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>