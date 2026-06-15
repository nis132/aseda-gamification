{{-- resources/views/leaderboard/sertifikat-juara.blade.php --}}
{{-- Dirender oleh DomPDF, ukuran A4 landscape --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'DejaVu Sans', sans-serif;
    width: 297mm; height: 210mm;
    background: #fff;
    color: #1a1a2e;
  }

  .border-outer {
    position: absolute;
    inset: 8mm;
    border: 4px solid #1a3a6b;
  }
  .border-inner {
    position: absolute;
    inset: 11mm;
    border: 1.5px solid #b8860b;
  }

  .content {
    position: absolute;
    inset: 14mm;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    gap: 6mm;
  }

  .header-logo {
    font-size: 11pt;
    color: #555;
    letter-spacing: 2px;
    text-transform: uppercase;
  }

  .judul {
    font-size: 28pt;
    font-weight: bold;
    color: #1a3a6b;
    letter-spacing: 3px;
    text-transform: uppercase;
    line-height: 1.1;
  }

  .subjudul {
    font-size: 12pt;
    color: #555;
  }

  .nama-juara {
    font-size: 22pt;
    font-weight: bold;
    color: #b8860b;
    padding-bottom: 5mm;
    min-width: 160mm;
  }

  .nis {
    font-size: 10pt;
    color: #777;
    margin-top: -4mm;
  }

  .keterangan {
    font-size: 11pt;
    color: #333;
    line-height: 1.8;
  }

  .keterangan strong {
    color: #1a3a6b;
  }

  .medali {
    font-size: 40pt;
    line-height: 1;
  }

  .footer {
    display: flex;
    justify-content: space-between;
    width: 100%;
    margin-top: 6mm;
    font-size: 9pt;
    color: #888;
  }

  .ttd {
    text-align: center;
  }

  .ttd .garis {
    width: 50mm;
    border-top: 1px solid #333;
    margin: 6mm auto 1mm;
  }

  .ttd p {
    font-size: 9pt;
    color: #333;
  }
</style>
</head>
<body>
  <div class="border-outer"></div>
  <div class="border-inner"></div>

  <div class="content">

    <p class="header-logo">SMP Negeri 2 Semen</p>

    <h1 class="judul">Sertifikat<br>{{ $namaJuara }}</h1>
    <p class="subjudul">Platform Pembelajaran Gamifikasi &mdash; {{ $data->nama_kelas }}</p>

    <p class="medali">
      @if($data->rank === 1) 🥇
      @elseif($data->rank === 2) 🥈
      @else 🥉
      @endif
    </p>

    <p class="nama-juara">{{ $siswa->nama }}</p>
    <p class="nis">NIS: {{ $siswa->nis }}</p>

    <p class="keterangan">
      Telah meraih <strong>{{ $namaJuara }} Kelas {{ $data->nama_kelas }}</strong><br>
      pada periode <strong>{{ $data->periode }}</strong><br>
    </p>

    <div class="footer">
      <div class="ttd">
        <div class="garis"></div>
        <p>Wali Kelas</p>
      </div>
      <div style="text-align:center; font-size:8pt; color:#aaa; align-self:flex-end;">
        Diterbitkan: {{ \Carbon\Carbon::parse($data->dikunci_pada)->translatedFormat('d F Y') }}
      </div>
      <div class="ttd">
        <div class="garis"></div>
        <p>Kepala Sekolah</p>
      </div>
    </div>

  </div>
</body>
</html>
