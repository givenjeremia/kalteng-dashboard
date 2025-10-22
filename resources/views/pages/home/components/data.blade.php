{{-- Box Info --}}
<div class="row align-items-stretch mb-3">
    {{-- === KOLOM KIRI === --}}
    <div class="col-md-6 d-flex flex-column">
        {{-- CARD PAGU --}}
        <div class="card bg-white text-dark p-3 mb-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-cash-stack fs-2 text-dark"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold">Pagu</div>
                    <div class="fs-4 fw-bold">
                        Rp{{ number_format($totalPagu, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD REALISASI --}}
        <div class="card bg-white text-dark p-3 mb-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-graph-up-arrow fs-2 text-dark"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold">Realisasi</div>
                    <div class="fs-4 fw-bold">
                        Rp{{ number_format($totalRealisasi, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL TRIWULAN --}}
        <div class="card bg-white text-dark p-3 flex-fill">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-bar-chart-fill me-2"></i>
                Persentase Realisasi per Triwulan
            </h6>

            <div class="table-responsive">
                <table class="table align-middle table-striped table-bordered text-center mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Triwulan</th>
                            <th>Periode</th>
                            <th>Pegawai</th>
                            <th>Barang</th>
                            <th>Modal</th>
                            <th>Total Realisasi</th>
                            <th>% dari Pagu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($triwulan_summary as $tw)
                            <tr>
                                <td class="fw-semibold">{{ $tw['kode'] }}</td>
                                <td>{{ $tw['periode'] }}</td>
                                <td>{{ number_format($tw['pegawai'], 0, ',', '.') }}</td>
                                <td>{{ number_format($tw['barang'], 0, ',', '.') }}</td>
                                <td>{{ number_format($tw['modal'], 0, ',', '.') }}</td>
                                <td class="fw-bold text-primary">
                                    {{ number_format($tw['total'], 0, ',', '.') }}
                                </td>
                                <td class="fw-bold text-success">{{ $tw['persentase'] }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6 d-flex flex-column">
        {{-- REALISASI TAHUNAN --}}
        <div class="card bg-white text-dark p-3 mb-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-graph-up-arrow fs-2 text-dark"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold">Realisasi dari Target Tahunan</div>
                    <div class="fs-4 fw-bold">% {{ $tahunanPercent }}</div>
                </div>
            </div>
        </div>

        <div class="card p-3 flex-fill d-flex flex-column justify-content-center">
            <div id="realiasasiGauge" class="border border-1 rounded"></div>
        </div>
    </div>
</div>

{{-- Chart --}}
{{-- 
        <div class="card bg-white text-dark p-3 mb-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-graph-up-arrow fs-2x text-dark"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold">Realisasi dari Target Triwulan</div>
                    <div class="fs-4 fw-bold">% {{ $triwulanPercent }}</div>
                </div>
            </div>
        </div> --}}
<div class="card card-flush my-3 ">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 border border-1 rounded"><div id="modalGauge"></div></div>
            <div class="col-md-4 border border-1 rounded"><div id="pegawaiGauge"></div></div>
            <div class="col-md-4 border border-1 rounded"><div id="belanjaGauge"></div></div>
        </div>
    </div>
</div>

<div class="card card-flush my-3">
    <div class="card-body">
        <div class="row mt-4">
            <div class="col-md-12"><div id="lineChart"></div></div>
        </div>
    </div>
</div>

<div class="row g-5">
    <!-- IKPA -->
    <div class="col-md-4">
        <div class="card text-center p-5 h-100" >
            <h5 class="fw-bold">IKPA</h5>
            <div class="text-success fs-1 fw-bolder mt-3">{{ $ikpaRataRata }}%</div>
            <div class="text-muted fs-6 mt-2">Keterangan IKPA: {{ $ikpaKeterangan }}</div>
        </div>
    </div>

    <!-- E-Performance -->
    <div class="col-md-4">
        <div class="card text-center p-5 h-100">
            <h5 class="fw-bold">E-Performance</h5>
            <div class="text-success fs-1 fw-bolder mt-3">{{ $eperformanceCapaian }}%</div>
        </div>
    </div>

    <!-- E-Monev Bappenas -->
    <div class="col-md-4">
        <div class="card p-5 h-100">
            <h5 class="fw-bold text-center">E-Monev Bappenas</h5>
            <div class="mt-4">
                <div class="d-flex justify-content-between">
                    <span class="fw-semibold text-gray-600">Status Pelaksanaan</span>
                    <span class="fw-bold">{{ $emonevKinerja }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="fw-semibold text-gray-600">Anggaran</span>
                    <span class="fw-bold">Rp{{ number_format($emonevAnggaran, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="fw-semibold text-gray-600">Keterangan</span>
                    <span class="fw-bold">{{ $emonevKeterangan}}</span>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Table --}}
<div class="card card-flush my-3">
    <div class="card-body">
        <h3 class="text-lg font-bold mb-4 text-gray-800">
            Histori Data Hingga Bulan {{ $bulan }} Pada Tahun {{ $tahun }}
        </h3>

        <div class="overflow-x-auto border rounded-lg shadow-sm">
            <table class="min-w-full border-collapse w-100">
                <thead>
                    <tr class="bg-biru-tua text-white text-sm text-center uppercase">
                        <th rowspan="2" class="border border-white py-3 px-2 w-36 align-middle">Satker</th>
                        <th colspan="3" class="border border-white py-2 px-2">Belanja Pegawai</th>
                        <th colspan="3" class="border border-white py-2 px-2">Belanja Barang</th>
                        <th colspan="3" class="border border-white py-2 px-2">Belanja Modal</th>
                        <th colspan="3" class="border border-white py-2 px-2">Total Belanja</th>
                    </tr>
                    <tr class="bg-biru-tua text-white text-xs">
                        <th class="border border-white py-1 px-2">Pagu</th>
                        <th class="border border-white py-1 px-2">Realisasi</th>
                        <th class="border border-white py-1 px-2">%</th>

                        <th class="border border-white py-1 px-2">Pagu</th>
                        <th class="border border-white py-1 px-2">Realisasi</th>
                        <th class="border border-white py-1 px-2">%</th>

                        <th class="border border-white py-1 px-2">Pagu</th>
                        <th class="border border-white py-1 px-2">Realisasi</th>
                        <th class="border border-white py-1 px-2">%</th>

                        <th class="border border-white py-1 px-2">Pagu</th>
                        <th class="border border-white py-1 px-2">Realisasi</th>
                        <th class="border border-white py-1 px-2">%</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-center text-gray-800">
                    @foreach($budgets_primitif as $b)
                        @php
                            $pegawai_per = $b->pagu_pegawai > 0 ? round(($b->realisasi_pegawai / $b->pagu_pegawai) * 100, 2) : 0;
                            $barang_per = $b->pagu_barang > 0 ? round(($b->realisasi_barang / $b->pagu_barang) * 100, 2) : 0;
                            $modal_per = $b->pagu_modal > 0 ? round(($b->realisasi_modal / $b->pagu_modal) * 100, 2) : 0;
                            $tp = $b->pagu_pegawai + $b->pagu_barang + $b->pagu_modal;
                            $tr = $b->realisasi_pegawai + $b->realisasi_barang + $b->realisasi_modal;
                            $total_per = $tp > 0 ? round(($tr / $tp) * 100, 2) : 0;
                        @endphp
                        <tr class="odd:bg-gray-50 even:bg-white hover:bg-blue-50 transition">
                            <td class="border border-gray-300 py-2 px-2 font-semibold">{{ $b->departement?->title ?? '-' }}</td>

                            {{-- Belanja Pegawai --}}
                            <td class="border border-gray-300 py-2 px-2">{{ $b->pagu_pegawai ? number_format($b->pagu_pegawai, 0, ',', '.') : '-' }}</td>
                            <td class="border border-gray-300 py-2 px-2">{{ $b->realisasi_pegawai ? number_format($b->realisasi_pegawai, 0, ',', '.') : '-' }}</td>
                            <td class="border border-gray-300 py-2 px-2">{{ $pegawai_per ?: '-' }}</td>

                            {{-- Belanja Barang --}}
                            <td class="border border-gray-300 py-2 px-2">{{ $b->pagu_barang ? number_format($b->pagu_barang, 0, ',', '.') : '-' }}</td>
                            <td class="border border-gray-300 py-2 px-2">{{ $b->realisasi_barang ? number_format($b->realisasi_barang, 0, ',', '.') : '-' }}</td>
                            <td class="border border-gray-300 py-2 px-2">{{ $barang_per ?: '-' }}</td>

                            {{-- Belanja Modal --}}
                            <td class="border border-gray-300 py-2 px-2">{{ $b->pagu_modal ? number_format($b->pagu_modal, 0, ',', '.') : '-' }}</td>
                            <td class="border border-gray-300 py-2 px-2">{{ $b->realisasi_modal ? number_format($b->realisasi_modal, 0, ',', '.') : '-' }}</td>
                            <td class="border border-gray-300 py-2 px-2">{{ $modal_per ?: '-' }}</td>

                            {{-- Total Belanja --}}
                            <td class="border border-gray-300 py-2 px-2 font-bold">{{ number_format($tp, 0, ',', '.') }}</td>
                            <td class="border border-gray-300 py-2 px-2 font-bold">{{ number_format($tr, 0, ',', '.') }}</td>
                            <td class="border border-gray-300 py-2 px-2 font-bold">{{ $total_per }}%</td>
                        </tr>
                    @endforeach

                    {{-- Baris TOTAL --}}
                    @php
                        $tp_total = $total_primitif['pagu_pegawai'] + $total_primitif['pagu_barang'] + $total_primitif['pagu_modal'];
                        $tr_total = $total_primitif['realisasi_pegawai'] + $total_primitif['realisasi_barang'] + $total_primitif['realisasi_modal'];
                        $total_persen = $tp_total > 0 ? round(($tr_total / $tp_total) * 100, 2) : 0;
                    @endphp
                    <tr class="bg-biru-tua text-white font-bold text-center">
                        <td class="border border-white py-2 px-2">TOTAL</td>
                        <td class="border border-white py-2 px-2">{{ number_format($total_primitif['pagu_pegawai'], 0, ',', '.') }}</td>
                        <td class="border border-white py-2 px-2">{{ number_format($total_primitif['realisasi_pegawai'], 0, ',', '.') }}</td>
                        <td class="border border-white py-2 px-2">{{ round(($total_primitif['pagu_pegawai'] > 0 ? $total_primitif['realisasi_pegawai'] / $total_primitif['pagu_pegawai'] * 100 : 0), 2) }}%</td>

                        <td class="border border-white py-2 px-2">{{ number_format($total_primitif['pagu_barang'], 0, ',', '.') }}</td>
                        <td class="border border-white py-2 px-2">{{ number_format($total_primitif['realisasi_barang'], 0, ',', '.') }}</td>
                        <td class="border border-white py-2 px-2">{{ round(($total_primitif['pagu_barang'] > 0 ? $total_primitif['realisasi_barang'] / $total_primitif['pagu_barang'] * 100 : 0), 2) }}%</td>

                        <td class="border border-white py-2 px-2">{{ number_format($total_primitif['pagu_modal'], 0, ',', '.') }}</td>
                        <td class="border border-white py-2 px-2">{{ number_format($total_primitif['realisasi_modal'], 0, ',', '.') }}</td>
                        <td class="border border-white py-2 px-2">{{ round(($total_primitif['pagu_modal'] > 0 ? $total_primitif['realisasi_modal'] / $total_primitif['pagu_modal'] * 100 : 0), 2) }}%</td>

                        <td class="border border-white py-2 px-2">{{ number_format($tp_total, 0, ',', '.') }}</td>
                        <td class="border border-white py-2 px-2">{{ number_format($tr_total, 0, ',', '.') }}</td>
                        <td class="border border-white py-2 px-2">{{ $total_persen }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

