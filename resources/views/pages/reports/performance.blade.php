<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>{{ $title }}</title>
<style>
  @page { margin: 28mm 18mm 22mm 18mm; }
  body  { font-family: DejaVu Sans, Arial, sans-serif; color:#0f172a; }

  .muted{ color:#64748b; font-size: 12px; }
  .sm   { font-size: 12px; }
  .md   { font-size: 14px; }
  .lg   { font-size: 18px; }
  .xl   { font-size: 26px; }
  .bold { font-weight:700; }
  .mt-8{ margin-top:32px; }
  .mt-4{ margin-top:16px; }
  .mb-2{ margin-bottom:8px; }
  .mb-4{ margin-bottom:16px; }
  .mb-8{ margin-bottom:32px; }

  .grid { display: table; width:100%; table-layout: fixed; }
  .col  { display: table-cell; vertical-align: top; }
  .right{ text-align:right; }

  header { position: fixed; top:-18mm; left:0; right:0; height:18mm; }
  footer { position: fixed; bottom:-14mm; left:0; right:0; height:14mm; color:#94a3b8; font-size:11px; }

  .bar-top {
    height: 18mm; background: linear-gradient(90deg,#0ea5e9 0%, #1d4ed8 100%);
    color:#fff; padding: 6mm 18mm;
    display:flex; align-items:center; justify-content:space-between;
  }
  .bar-top .title { font-size:16px; font-weight:700; letter-spacing:.5px; }
  .bar-top .subtitle { font-size:12px; opacity:.9; }

  .page-number:after { content: counter(page); }

  .section-title {
    background:#e0f2fe; border-left:4px solid #0284c7;
    padding:10px 12px; font-weight:700;
    color:#0c4a6e; margin:22px 0 12px;
  }

  table { width:100%; border-collapse: collapse; }
  th, td { padding:8px; border-bottom:1px solid #e2e8f0; font-size:12px; }
  th { background:#f1f5f9; color:#334155; }

  .progress { background:#e2e8f0; height:8px; border-radius:999px; overflow:hidden; }
  .progress > span { display:block; height:8px; background:#3b82f6; }

  .cover {
    height: 60vh; display:flex; flex-direction:column;
    justify-content:center; align-items:center;
    background: linear-gradient(180deg,#e0f2fe 0%, #ffffff 60%);
    border:1px solid #e2e8f0; border-radius:12px;
    padding:24px 28px; text-align:center;
  }

  .cover h1 { font-size:34px; color:#0b3b73; margin:0 0 6px; }
  .cover h2 { font-size:18px; color:#2563eb; margin:0 0 16px; }

  .logo { height:28px; }
  .page-break { page-break-after: always; }

</style>
</head>
<body>

<header>
  <div class="bar-top">
    <div>
      <div class="title">{{ $title }}</div>
      <div class="subtitle">{{ $instansi }}</div>
    </div>
    @if(is_file($logo))
      <img class="logo" src="{{ $logo }}" alt="logo">
    @endif
  </div>
</header>

<footer>
<div class="grid">
  <div class="col">Generated {{ $tanggal }}</div>
  <div class="col right">Halaman <span class="page-number"></span></div>
</div>
</footer>

{{-- ================= COVER ================= --}}
<div class="cover">
  <h1>{{ $title }}</h1>
  <h2>{{ strtoupper($subtitle) }}</h2>
  <div class="muted">{{ $instansi }}</div>
  <div class="muted mt-4">Dokumen otomatis dari sistem</div>
</div>

<div class="page-break"></div>

{{-- ================= IKK ================= --}}
<div class="section-title">INDIKATOR KINERJA KEGIATAN (IKK) – {{ $subtitle }}</div>
<table class="mb-8">
<thead>
<tr>
  <th>No</th>
  <th>Sasaran</th>
  <th>Indikator</th>
  <th>Target</th>
  <th>Tercapai</th>
  <th>Persentase Capaian</th>
</tr>
</thead>
<tbody>
@foreach($data_eperformance as $i => $data)
<tr>
  <td>{{ $i+1 }}</td>
  <td>{{ $data->sasaran }}</td>
  <td>{{ $data->indikator }}</td>
  <td>{{ number_format($data->target, 2, ',', '.') }}</td>
  <td>{{ number_format($data->tercapai, 2, ',', '.') }}</td>
  <td>{{ number_format($data->persentase_capaian, 2, ',', '.') }}%</td>
</tr>
@endforeach
</tbody>
</table>

{{-- ================= PAGU ================= --}}
<div class="section-title">PAGU ANGGARAN (NON-BLOKIR) – {{ $subtitle }}</div>
<table class="mb-8">
<thead>
<tr>
  <th>Komponen</th>
  <th class="right">Nilai</th>
</tr>
</thead>
<tbody>
@foreach($pagu as $row)
<tr>
  <td>{{ $row['label'] }}</td>
  <td class="right">Rp {{ number_format($row['value'],0,',','.') }}</td>
</tr>
@endforeach

<tr>
  <th>Total</th>
  <th class="right">Rp {{ number_format(array_sum(array_column($pagu,'value')),0,',','.') }}</th>
</tr>
</tbody>
</table>

{{-- ================= REALISASI ================= --}}
<div class="section-title">REALISASI ANGGARAN (NON-BLOKIR)</div>
<table class="mb-8">
<thead>
<tr>
  <th>Uraian</th>
  <th class="right">Nilai</th>
</tr>
</thead>
<tbody>
@foreach($realisasi as $row)
<tr>
  <td>{{ $row['label'] }}</td>
  <td class="right">Rp {{ number_format($row['value'],0,',','.') }}</td>
</tr>
@endforeach
<tr>
  <th>Total</th>
  <th class="right">Rp {{ number_format(array_sum(array_column($realisasi,'value')),0,',','.') }}</th>
</tr>
</tbody>
</table>

{{-- ================= IKPA ================= --}}
<div class="page-break"></div>
<div class="section-title">INDIKATOR KINERJA PELAKSANAAN ANGGARAN (IKPA)</div>
<table style="width:100%; border-collapse:collapse; font-size:11px; margin-bottom:20px;">
    <thead>
        <tr style="background:#1d4ed8; color:white;">
            <th style="border:1px solid #fff; padding:5px;">Satker</th>
            <th style="border:1px solid #fff; padding:5px;">Deviasi DIPA</th>
            <th style="border:1px solid #fff; padding:5px;">Revisi DIPA</th>
            <th style="border:1px solid #fff; padding:5px;">Penyerapan</th>
            <th style="border:1px solid #fff; padding:5px;">Output</th>
            <th style="border:1px solid #fff; padding:5px;">Tagihan</th>
            <th style="border:1px solid #fff; padding:5px;">UP/TUP</th>
            <th style="border:1px solid #fff; padding:5px;">Kontraktual</th>
            <th style="border:1px solid #fff; padding:5px;">Nilai IKPA</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data_ikpa as $item)
        @php
            // Warna berdasarkan nilai IKPA
            $color = $item->nilai_ikpa >= 95 ? '#059669'
                : ($item->nilai_ikpa >= 80 ? '#d97706' : '#dc2626');
        @endphp
        <tr>
            <td style="border:1px solid #ddd; padding:5px; font-weight:bold;">
                {{ $item->departement?->title ?? '-' }}
            </td>
            <td style="border:1px solid #ddd; padding:5px; text-align:right;">
                {{ number_format($item->deviation_dipa,2,',','.') }}%
            </td>
            <td style="border:1px solid #ddd; padding:5px; text-align:right;">
                {{ number_format($item->revisi_dipa,2,',','.') }}%
            </td>
            <td style="border:1px solid #ddd; padding:5px; text-align:right;">
                {{ number_format($item->penyerapan_anggaran,2,',','.') }}%
            </td>
            <td style="border:1px solid #ddd; padding:5px; text-align:right;">
                {{ number_format($item->capaian_output,2,',','.') }}%
            </td>
            <td style="border:1px solid #ddd; padding:5px; text-align:right;">
                {{ number_format($item->penyelesaian_tagihan,2,',','.') }}%
            </td>
            <td style="border:1px solid #ddd; padding:5px; text-align:right;">
                {{ number_format($item->pengelolaan_up_tup,2,',','.') }}%
            </td>
            <td style="border:1px solid #ddd; padding:5px; text-align:right;">
                {{ number_format($item->belanja_kontraktual,2,',','.') }}%
            </td>
            <td style="border:1px solid #ddd; padding:5px; font-weight:bold; text-align:right; color:{{ $color }}">
                {{ number_format($item->nilai_ikpa,2,',','.') }}%
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="page-break"></div>

{{-- ================= HISTORI PER SATKER ================= --}}
<div class="section-title">HISTORI REALISASI PER SATKER – {{ $subtitle }}</div>

<table style="width:100%; border-collapse:collapse; font-size:11px; margin-bottom:24px;">
<thead>
<tr style="background:#1d4ed8; color:white;">
  <th rowspan="2" style="border:1px solid #fff; padding:4px;">Satker</th>
  <th colspan="3" style="border:1px solid #fff; padding:4px;">Belanja Pegawai</th>
  <th colspan="3" style="border:1px solid #fff; padding:4px;">Belanja Barang</th>
  <th colspan="3" style="border:1px solid #fff; padding:4px;">Belanja Modal</th>
  <th colspan="3" style="border:1px solid #fff; padding:4px;">Total Belanja</th>
</tr>
<tr style="background:#1d4ed8; color:white;">
  <th style="border:1px solid #fff; padding:3px;">Pagu</th>
  <th style="border:1px solid #fff; padding:3px;">Realisasi</th>
  <th style="border:1px solid #fff; padding:3px;">%</th>
  <th style="border:1px solid #fff; padding:3px;">Pagu</th>
  <th style="border:1px solid #fff; padding:3px;">Realisasi</th>
  <th style="border:1px solid #fff; padding:3px;">%</th>
  <th style="border:1px solid #fff; padding:3px;">Pagu</th>
  <th style="border:1px solid #fff; padding:3px;">Realisasi</th>
  <th style="border:1px solid #fff; padding:3px;">%</th>
  <th style="border:1px solid #fff; padding:3px;">Pagu</th>
  <th style="border:1px solid #fff; padding:3px;">Realisasi</th>
  <th style="border:1px solid #fff; padding:3px;">%</th>
</tr>
</thead>

<tbody>
@foreach($budgets_primitif as $b)
@php
    $pegawai_per = $b->pagu_pegawai > 0 ? round(($b->realisasi_pegawai / $b->pagu_pegawai) * 100, 2) : 0;
    $barang_per = $b->pagu_barang > 0 ? round(($b->realisasi_barang / $b->pagu_barang) * 100, 2) : 0;
    $modal_per = $b->pagu_modal > 0 ? round(($b->realisasi_modal / $b->pagu_modal) * 100, 2) : 0;
    $tp = $b->pagu_pegawai + $b->pagu_barang + $b->pagu_modal;
    $tr = $b->realisasi_pegawai + $b->realisasi_barang + $b->realisasi_modal;
    $total_per = $tp > 0 ? round(($tr / $tp) * 100, 2) : 0;
@endphp
<tr>
  <td style="border:1px solid #ccc; padding:3px; font-weight:bold;">
    {{ $b->departement?->title ?? '-' }}
  </td>

  <td style="border:1px solid #ccc; padding:3px;">{{ number_format($b->pagu_pegawai,0,',','.') }}</td>
  <td style="border:1px solid #ccc; padding:3px;">{{ number_format($b->realisasi_pegawai,0,',','.') }}</td>
  <td style="border:1px solid #ccc; padding:3px;">{{ $pegawai_per }}%</td>

  <td style="border:1px solid #ccc; padding:3px;">{{ number_format($b->pagu_barang,0,',','.') }}</td>
  <td style="border:1px solid #ccc; padding:3px;">{{ number_format($b->realisasi_barang,0,',','.') }}</td>
  <td style="border:1px solid #ccc; padding:3px;">{{ $barang_per }}%</td>

  <td style="border:1px solid #ccc; padding:3px;">{{ number_format($b->pagu_modal,0,',','.') }}</td>
  <td style="border:1px solid #ccc; padding:3px;">{{ number_format($b->realisasi_modal,0,',','.') }}</td>
  <td style="border:1px solid #ccc; padding:3px;">{{ $modal_per }}%</td>

  <td style="border:1px solid #ccc; padding:3px; font-weight:bold;">{{ number_format($tp,0,',','.') }}</td>
  <td style="border:1px solid #ccc; padding:3px; font-weight:bold;">{{ number_format($tr,0,',','.') }}</td>
  <td style="border:1px solid #ccc; padding:3px; font-weight:bold;">{{ $total_per }}%</td>
</tr>
@endforeach

{{-- TOTAL --}}
@php
    $tp_total = $total_primitif['pagu_pegawai'] + $total_primitif['pagu_barang'] + $total_primitif['pagu_modal'];
    $tr_total = $total_primitif['realisasi_pegawai'] + $total_primitif['realisasi_barang'] + $total_primitif['realisasi_modal'];
    $total_persen = $tp_total > 0 ? round(($tr_total / $tp_total) * 100, 2) : 0;
@endphp

<tr style="background:#1d4ed8; color:white; font-weight:bold;">
  <td style="border:1px solid #fff; padding:3px;">TOTAL</td>
  <td style="border:1px solid #fff; padding:3px;">{{ number_format($total_primitif['pagu_pegawai'],0,',','.') }}</td>
  <td style="border:1px solid #fff; padding:3px;">{{ number_format($total_primitif['realisasi_pegawai'],0,',','.') }}</td>
  <td style="border:1px solid #fff; padding:3px;">{{ round(($total_primitif['realisasi_pegawai'] / $total_primitif['pagu_pegawai'])*100,2) }}%</td>

  <td style="border:1px solid #fff; padding:3px;">{{ number_format($total_primitif['pagu_barang'],0,',','.') }}</td>
  <td style="border:1px solid #fff; padding:3px;">{{ number_format($total_primitif['realisasi_barang'],0,',','.') }}</td>
  <td style="border:1px solid #fff; padding:3px;">{{ round(($total_primitif['realisasi_barang'] / $total_primitif['pagu_barang'])*100,2) }}%</td>

  <td style="border:1px solid #fff; padding:3px;">{{ number_format($total_primitif['pagu_modal'],0,',','.') }}</td>
  <td style="border:1px solid #fff; padding:3px;">{{ number_format($total_primitif['realisasi_modal'],0,',','.') }}</td>
  <td style="border:1px solid #fff; padding:3px;">{{ round(($total_primitif['realisasi_modal'] / $total_primitif['pagu_modal'])*100,2) }}%</td>

  <td style="border:1px solid #fff; padding:3px;">{{ number_format($tp_total,0,',','.') }}</td>
  <td style="border:1px solid #fff; padding:3px;">{{ number_format($tr_total,0,',','.') }}</td>
  <td style="border:1px solid #fff; padding:3px;">{{ $total_persen }}%</td>
</tr>

</tbody>
</table>

<div class="page-break"></div>

{{-- ================= TERIMA KASIH ================= --}}
<div class="section-title">TERIMA KASIH</div>
<p class="sm muted">Dokumen ini dihasilkan otomatis. Hubungi admin bila ada koreksi data.</p>

</body>
</html>
