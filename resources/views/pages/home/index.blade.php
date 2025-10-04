@extends('layouts.base')
@section('title','Dashboard')

@section('toolbar')
@include('components/toolbar',['title' => 'Dashboard'])
@endsection

@section('content')

<div class="container-fluid">

    {{-- Filter --}}
    <form id="filter-form" class="mb-5">
        <div class="card card-flush">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <label for="exampleFormControlInput1" class=" form-label">Pilih Tahun</label>
                        <select class="form-select" name="tahun" id="tahun" data-control="select2" data-placeholder="Pilih Tahun" data-hide-search="true">
                            @for($t=2024; $t<=2025; $t++)
                                <option value="{{ $t }}" {{ $tahun==$t ? 'selected':'' }}>{{ $t }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col">
                        <label for="exampleFormControlInput1" class="form-label">Pilih Bulan</label>
                        <select class="form-select" name="bulan" id="bulan" data-control="select2" data-placeholder="Pilih Tahun" data-hide-search="true">
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}" {{ $bulan==$m ? 'selected':'' }}>
                                    {{ date("F", mktime(0,0,0,$m,1)) }}
                                </option>
                            @endfor
                        </select>
                        
                    </div>
                </div>


            </div>
        </div>
    
      
    </form>

    {{-- Tempat isi dashboard --}}
    <div id="dashboard-content"></div>
</div>

@endsection

@section('scripts')
<script>
    function initDashboardCharts(persentase, pegawai, barang, monthlyData, monthlyPagu, monthlyRealisasi, categories){

        new ApexCharts(document.querySelector("#gauge"), {
            chart: { type: 'radialBar' },
            series: [persentase],
            labels: ['% Realisasi']
        }).render();


        new ApexCharts(document.querySelector("#pegawaiGauge"), {
            chart: { type: 'radialBar' },
            series: [pegawai],
            labels: ['Pegawai']
        }).render();


        new ApexCharts(document.querySelector("#belanjaGauge"), {
            chart: { type: 'radialBar' },
            series: [barang],
            labels: ['Barang']
        }).render();

        new ApexCharts(document.querySelector("#lineChart"), {
            chart: { height: 350, type: 'line' },
            series: [
                {
                    name: 'Pagu',
                    type: 'column',
                    data: monthlyPagu
                },
                {
                    name: 'Realisasi',
                    type: 'line',
                    data: monthlyRealisasi
                }
                // Kalau mau % realisasi:
                // {
                //     name: '% Realisasi',
                //     type: 'line',
                //     data: monthlyData
                // }
            ],
            stroke: { width: [0, 4] },
            xaxis: {
                categories: categories
            },
            yaxis: [{
                title: { text: "Rp" },
                labels: {
                    formatter: function (val) {
                        return "Rp " + val.toLocaleString();
                    }
                }
            }],
            tooltip: {
                shared: true,
                y: {
                    formatter: function (val) {
                        return "Rp " + val.toLocaleString();
                    }
                }
            }
        }).render();
    }



    function loadDashboard(){
        let tahun = document.getElementById('tahun').value;
        let bulan = document.getElementById('bulan').value;

        fetch(`/dashboard/data?tahun=${tahun}&bulan=${bulan}`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success'){
                    document.getElementById('dashboard-content').innerHTML = data.html;
                    initDashboardCharts(
                        data.persentase, 
                        data.pegawaiPercent, 
                        data.barangPercent, 
                        data.monthlyData, 
                        data.monthlyPagu, 
                        data.monthlyRealisasi,
                        data.categories 
                    );
                }
            });
    }



    loadDashboard();

    $(document).ready(function() {
        $('#tahun').on('change', function() {
            loadDashboard();
        });

        $('#bulan').on('change', function() {
            loadDashboard();
        });
    });

</script>
@endsection