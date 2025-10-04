{{-- Box Info --}}
<div class="row mb-3">
    <div class="col-md-4">
        <div class="card bg-primary text-white p-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-cash-stack fs-2x text-white"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold">Pagu</div>
                    <div class="fs-4 fw-bold">Rp{{ number_format($totalPagu,0,',','.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-primary text-white p-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-graph-up-arrow fs-2x text-white"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold">Realisasi</div>
                    <div class="fs-4 fw-bold">Rp{{ number_format($totalRealisasi,0,',','.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-primary text-white p-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="bi bi-percent fs-2x text-white"></i>
                </div>
                <div>
                    <div class="fs-6 fw-semibold">% Realisasi</div>
                    <div class="fs-4 fw-bold">{{ $persentase }}%</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart --}}

<div class="card card-flush my-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4"><div id="gauge"></div></div>
            <div class="col-md-4"><div id="pegawaiGauge"></div></div>
            <div class="col-md-4"><div id="belanjaGauge"></div></div>
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


{{-- Table --}}
<div class="card card-flush my-3">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-12">
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
        </div>
    </div>
</div>

