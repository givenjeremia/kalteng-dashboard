@extends('layouts.base')
@section('title','Performance')

@section('toolbar')
@include('components/toolbar',['title' => 'Performance'])
@endsection

@section('content')


<div class="container-fluid">
    @php
        $tahunSekarang = date('Y');
        $bulanSekarang = date('n');
    @endphp

    <form id="filter-form" class="mb-5">
        <div class="card card-flush">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <label class="form-label">Pilih Tahun</label>
                        <select class="form-select" name="tahun" id="tahun" data-control="select2" data-placeholder="Pilih Tahun" data-hide-search="true">
                            @for($t = 2024; $t <= 2025; $t++)
                                <option value="{{ $t }}" {{ $t == $tahunSekarang ? 'selected' : '' }}>
                                    {{ $t }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col">
                        <label class="form-label">Pilih Bulan</label>
                        <select class="form-select" name="bulan" id="bulan" data-control="select2" data-placeholder="Pilih Bulan" data-hide-search="true">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == $bulanSekarang ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div id="contents"></div>


      
  
</div>



<div id="modal-div"></div>

@endsection

@section('scripts')
<script>
    function loadData(){
        const tahun = $('#tahun').val();
        const bulan = $('#bulan').val();
        $.ajax({
            url: "{{ route('performances.index') }}"
            , method: "GET",
            data: { tahun, bulan }
            , success: function(response) {
                $('#contents').html("");
                if (response.status == 'success') {
                    $('#contents').html(response.msg);
                }
                else{
                    Swal.fire({
                        title: response.msg,
                        icon:'error'
                    })
                }
            }
            , error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }
    $(document).ready(function() {
        const today = new Date();
        const bulanSekarang = today.getMonth() + 1;
        const tahunSekarang = today.getFullYear();

        $('#bulan').val(bulanSekarang).trigger('change');
        $('#tahun').val(tahunSekarang).trigger('change');

        loadData();

        $('#tahun, #bulan').on('change', function() {
            loadData();
        });
    });
</script>
@endsection