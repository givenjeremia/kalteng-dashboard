{{-- Box Info --}}
<div class="row mb-3">
    <div class="col-md-6">
        <div class="card bg-white text-dark p-3 mb-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-cash-stack fs-2x text-dark"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold">Pagu</div>
                    <div class="fs-4 fw-bold">Rp{{ number_format($totalPagu,0,',','.') }}</div>
                </div>
            </div>
        </div>
        <div class="card bg-white text-dark p-3 mb-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-graph-up-arrow fs-2x text-dark"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold">Realisasi</div>
                    <div class="fs-4 fw-bold">Rp{{ number_format($totalRealisasi,0,',','.') }}</div>
                </div>
            </div>
        </div>

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
        </div>

        <div class="card bg-white text-dark p-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-graph-up-arrow fs-2x text-dark"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold">Realisasi dari Target Tahunan</div>
                    <div class="fs-4 fw-bold">% {{ $tahunanPercent }}</div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-6">
        <div class="card p-3">
            <div id="realiasasiGauge"></div>
        </div>
    </div>
</div>

{{-- Chart --}}

<div class="card card-flush my-3">
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
                    <span class="fw-semibold text-gray-600">Kinerja Satker</span>
                    <span class="fw-bold">{{ $emonevKinerja }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="fw-semibold text-gray-600">Anggaran</span>
                    <span class="fw-bold">{{ $emonevAnggaran }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="fw-semibold text-gray-600">Fisik</span>
                    <span class="fw-bold">{{ $emonevFisik }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="fw-semibold text-gray-600">GAP</span>
                    <span class="fw-bold">{{ $emonevGAP }}</span>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Table --}}
{{-- <div class="card card-flush my-3">
    <div class="card-body">
        <h3>Histori Data Hingga Bulan {{ $bulan }} Pada Tahun {{ $tahun }}</h3>
        <table class="table table-hover table-rounded table-striped border gy-7 gs-7" id="budget-table">
            <thead>
                <tr class="fw-semibold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                    <th>Departement</th>
                    <th>Bulan / Tahun</th>
                    <th>Pagu Pegawai</th>
                    <th>Realisasi Pegawai</th>
                    <th>Pagu Barang</th>
                    <th>Realisasi Barang</th>
                    <th>Pagu Modal</th>
                    <th>Realisasi Modal</th>
                    <th>Total Pagu</th>
                    <th>Total Realisasi</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                @foreach($budgets as $b)
                    @php
                        $tp = $b->pagu_pegawai + $b->pagu_barang + $b->pagu_modal;
                        $tr = $b->realisasi_pegawai + $b->realisasi_barang + $b->realisasi_modal;
                        $per = $tp > 0 ? round(($tr/$tp)*100,2) : 0;
                        if ($b->bulan == 8){

                            // dd($b->bulan);
                        }
                    @endphp
                    <tr>
                        <td>{{ $b->departement?->title ?? '-' }}</td>
                        <td>{{ $b->bulan }} / {{ $b->tahun }}</td>
                        <td>{{ number_format($b->pagu_pegawai) }}</td>
                        <td>{{ number_format($b->realisasi_pegawai) }}</td>
                        <td>{{ number_format($b->pagu_barang) }}</td>
                        <td>{{ number_format($b->realisasi_barang) }}</td>
                        <td>{{ number_format($b->pagu_modal) }}</td>
                        <td>{{ number_format($b->realisasi_modal) }}</td>
                        <td>{{ number_format($tp) }}</td>
                        <td>{{ number_format($tr) }}</td>
                        <td>{{ $per }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>
</div> --}}

