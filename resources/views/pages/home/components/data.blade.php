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
        <h3>Histori Data Hingga Bulan {{ $bulan }} Pada Tahun {{ $tahun }}</h3>

        <table class="table table-hover table-rounded table-striped border gy-7 gs-7" id="budget-table">
            <thead>
                <tr class="fw-semibold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                    <th>Departemen</th>
                    <th>Pagu Pegawai</th>
                    <th>Realisasi Pegawai</th>
                    <th>%</th>
                    <th>Pagu Barang</th>
                    <th>Realisasi Barang</th>
                    <th>%</th>
                    <th>Pagu Modal</th>
                    <th>Realisasi Modal</th>
                    <th>%</th>
                    <th>Total Pagu</th>
                    <th>Total Realisasi</th>
                    <th>%</th>
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
                        <td>{{ $b->departement?->title ?? '-' }}</td>
                        <td>{{ number_format($b->pagu_pegawai) }}</td>
                        <td>{{ number_format($b->realisasi_pegawai) }}</td>
                        <td>{{ $pegawai_per }}%</td>

                        <td>{{ number_format($b->pagu_barang) }}</td>
                        <td>{{ number_format($b->realisasi_barang) }}</td>
                        <td>{{ $barang_per }}%</td>

                        <td>{{ number_format($b->pagu_modal) }}</td>
                        <td>{{ number_format($b->realisasi_modal) }}</td>
                        <td>{{ $modal_per }}%</td>

                        <td>{{ number_format($tp) }}</td>
                        <td>{{ number_format($tr) }}</td>
                        <td>{{ $total_per }}%</td>
                    </tr>
                @endforeach

                {{-- Baris TOTAL --}}
                @php
                    $tp_total = $total_primitif['pagu_pegawai'] + $total_primitif['pagu_barang'] + $total_primitif['pagu_modal'];
                    $tr_total = $total_primitif['realisasi_pegawai'] + $total_primitif['realisasi_barang'] + $total_primitif['realisasi_modal'];
                    $total_persen = $tp_total > 0 ? round(($tr_total / $tp_total) * 100, 2) : 0;
                    // $total = total_primitif;
                @endphp
                <tr class="fw-bold text-dark border-top-2 border-gray-300">
                    <td>TOTAL</td>
                    <td>{{ number_format($total_primitif['pagu_pegawai']) }}</td>
                    <td>{{ number_format($total_primitif['realisasi_pegawai']) }}</td>
                    <td>{{ round(($total_primitif['pagu_pegawai'] > 0 ? $total_primitif['realisasi_pegawai'] / $total_primitif['pagu_pegawai'] * 100 : 0), 2) }}%</td>

                    <td>{{ number_format($total_primitif['pagu_barang']) }}</td>
                    <td>{{ number_format($total_primitif['realisasi_barang']) }}</td>
                    <td>{{ round(($total_primitif['pagu_barang'] > 0 ? $total_primitif['realisasi_barang'] / $total_primitif['pagu_barang'] * 100 : 0), 2) }}%</td>

                    <td>{{ number_format($total_primitif['pagu_modal']) }}</td>
                    <td>{{ number_format($total_primitif['realisasi_modal']) }}</td>
                    <td>{{ round(($total_primitif['pagu_modal'] > 0 ? $total_primitif['realisasi_modal'] /$total_primitif['pagu_modal'] * 100 : 0), 2) }}%</td>

                    <td>{{ number_format($tp_total) }}</td>
                    <td>{{ number_format($tr_total) }}</td>
                    <td>{{ $total_persen }}%</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

