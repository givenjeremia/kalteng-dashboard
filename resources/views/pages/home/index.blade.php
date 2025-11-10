@extends('layouts.base')
@section('title','Dashboard')

@section('toolbar')
@include('components/toolbar',['title' => 'Dashboard'])
@endsection

@section('styles')
<style>
    .gauge-box {
      width: 500px;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 15px;
      background: #fff;
      font-family: sans-serif;
    }
    .gauge-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: -40px;
    }
    .gauge-header span {
      background: #002d4f;
      color: white;
      padding: 5px 15px;
      border-radius: 4px;
      font-weight: bold;
    }
    .gauge-footer {
        display: flex;
        justify-content: space-between;
        margin-top: -25px;   /* lebih rapat */
        font-weight: bold;
        font-size: 14px;     /* opsional biar lebih proporsional */
    }

    .gauge-center {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -20%);
      font-size: 16px;
      font-weight: bold;
    }
    .gauge-wrapper {
      position: relative;
      margin-bottom: -25px; 
    }
</style>
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
                    <div class="col">
                        <label for="exampleFormControlInput1" class="form-label"></label>
                        <br>
                        <a href="{{ route('reports.pdf') }}" class="btn btn-primary">Download PDF</a>
                        
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
    function formatRupiah(angka) {
      return "Rp" + angka.toLocaleString("id-ID");
    }
</script>

<script>
    function createGauge(containerId, title, realisasi, total) {
      const persen = (realisasi / total * 100);

      const container = document.getElementById(containerId);
      container.innerHTML = `
        <div class="gauge-header">
          <div>${title}</div>
          <span id="${containerId}-persen">0%</span>
        </div>
        <div class="gauge-wrapper">
          <div id="${containerId}-chart"></div>
          <div id="${containerId}-rupiah" class="gauge-center">Rp0</div>
        </div>
        <div class="gauge-footer">
          <span id="${containerId}-left">Rp0</span>
          <span id="${containerId}-right">Rp0</span>
        </div>
      `;


      var options = {
        chart: { type: 'radialBar', height: 300 },
        series: [persen],
        plotOptions: {
          radialBar: {
            // startAngle: -90,
            // endAngle: 90,
            hollow: { size: "65%" },
            track: { background: "#e7e7e7", strokeWidth: "97%", margin: 0 },
            dataLabels: { show: false }
          }
        },
        fill: {
          colors: [persen > 80 ? "#4CAF50" : "#e53935"] 
        },
        stroke: { lineCap: "round" }
      };

      var chart = new ApexCharts(document.getElementById(`${containerId}-chart`), options);
      chart.render();

      document.getElementById(`${containerId}-persen`).innerText = persen.toFixed(2) + "%";
      document.getElementById(`${containerId}-rupiah`).innerText = formatRupiah(realisasi);
      document.getElementById(`${containerId}-left`).innerText = formatRupiah(0);
      document.getElementById(`${containerId}-right`).innerText = formatRupiah(total);
    }
</script>

<script>
    function initDashboardCharts(data){
        console.log(data)
        createGauge("realiasasiGauge", "Realisasi Terhadap Target", data.totalRealisasi, data.totalPagu);
        createGauge("modalGauge", "Realisasi Modal", data.modalRealisasi, data.modalTotal);
        createGauge("pegawaiGauge", "Realisasi Pegawai", data.pegawaiRealisasi, data.pegawaiTotal);
        createGauge("belanjaGauge", "Realisasi Barang", data.barangRealisasi, data.barangTotal);
        
        new ApexCharts(document.querySelector("#lineChart"), {
            chart: { height: 350, type: 'line' },
            series: [
                {
                    name: 'Pagu',
                    type: 'column',
                    data: data.monthlyPagu
                },
                {
                    name: 'Realisasi',
                    type: 'line',
                    data: data.monthlyRealisasi
                }
            ],
            stroke: { width: [0, 4] },
            xaxis: {
                categories: data.categories
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
                    initDashboardCharts(data);
                    $("#budget-table").DataTable({
                        responsive: true,
                        searchDelay: 500,
                        processing: true,
                        pageLength: 10,
                        lengthMenu: [5, 10, 25, 50],
                        order: [[1, 'asc']], 
                
                        language: {
                            lengthMenu: "Show _MENU_",
                            info: "Showing _START_ to _END_ of _TOTAL_ records",
                            infoEmpty: "No records available",
                        },
                

                        dom:
                            "<'row'" +
                            "<'col-sm-6 d-flex align-items-center justify-content-start'l>" +
                            "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                            ">" +
                            "tr" +
                            "<'row'" +
                            "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-start'i>" +
                            "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-end'p>" +
                            ">"
                    });
                }
                else{
                    var msg = data.msg
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: msg,
                    });
                }
            });
    }
  
    loadDashboard();
  
    $(document).ready(function() {
        $('#tahun, #bulan').on('change', function() {
            loadDashboard();
        });
    });
  </script>
  
@endsection