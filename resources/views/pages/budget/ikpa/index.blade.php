@extends('layouts.base')
@section('title','Budget - IKPA')

@section('toolbar')
@include('components/toolbar',['title' => 'Budget/IKPA'])
@endsection

@section('content')

<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title d-flex align-items-center gap-3">
            <!-- 🔍 Search -->
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input 
                    type="text"
                    data-kt-filter="search-ikpa"
                    class="form-control form-control-solid w-250px ps-12"
                    placeholder="Search Departemen / Tahun"
                />
            </div>

            <!-- 📅 Filter Bulan -->
            <div class="ms-3">
                <select id="filterBulan" class="form-select form-select-solid w-150px">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 📆 Filter Tahun -->
            <div class="ms-3">
                <select id="filterTahun" class="form-select form-select-solid w-120px">
                    @php
                        $currentYear = date('Y');
                    @endphp
                    @foreach(range($currentYear - 2, $currentYear + 1) as $y)
                        <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card-toolbar">
            <a href="#" id="btnTambah" class="btn btn-dark">Tambah</a>
        </div>
    </div>

    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table"></table>
    </div>
</div>

<div id="modal-div"></div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {

    // Inisialisasi DataTable
    var datatableIkpa = $('#kt_table').DataTable({
        initComplete: function() {
            $('#kt_table thead').addClass('bg-light-secondary fw-bold');
        },
        columnDefs: [{ defaultContent: "-", targets: "_all" }],
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('ikpa-score.index') }}",
            data: function (d) {
                // kirim parameter tambahan
                d.bulan = $('#filterBulan').val();
                d.tahun = $('#filterTahun').val();
            }
        },
        columns: [
            { data: 'No', name: 'No', title: 'No', className: 'px-5 text-nowrap' },
            { data: 'Departement', name: 'Departement', title: 'Departement', className: 'text-nowrap' },
            { data: 'Bulan', name: 'Bulan', title: 'Bulan', className: 'text-nowrap' },
            { data: 'Tahun', name: 'Tahun', title: 'Tahun', className: 'text-nowrap' },
            { data: 'Deviation DIPA', name: 'Deviation DIPA', title: 'Halaman III DIPA', className: 'text-nowrap' },
            { data: 'Revisi DIPA', name: 'Revisi DIPA', title: 'Revisi DIPA (%)', className: 'text-nowrap' },
            { data: 'Penyerapan Anggaran', name: 'Penyerapan Anggaran', title: 'Penyerapan Anggaran (%)', className: 'text-nowrap' },
            { data: 'Capaian Output', name: 'Capaian Output', title: 'Capaian Output (%)', className: 'text-nowrap' },
            { data: 'Penyelesaian Tagihan', name: 'Penyelesaian Tagihan', title: 'Penyelesaian Tagihan (%)', className: 'text-nowrap' },
            { data: 'Pengelolaan UP/TUP', name: 'Pengelolaan UP/TUP', title: 'Pengelolaan UP/TUP (%)', className: 'text-nowrap' },
            { data: 'Belanja Kontraktual', name: 'Belanja Kontraktual', title: 'Belanja Kontraktual (%)', className: 'text-nowrap' },
            { data: 'Nilai IKPA', name: 'Nilai IKPA', title: 'Nilai IKPA', className: 'text-nowrap fw-bold' },
            { data: 'Action', name: 'Action', title: 'Action', className: 'px-5 text-nowrap' }
        ]
    });

    // 🔍 Filter Pencarian
    const filterSearch = document.querySelector('[data-kt-filter="search-ikpa"]');
    filterSearch.addEventListener('keyup', function(e) {
        datatableIkpa.search(e.target.value).draw();
    });

    // 📅 Filter Bulan & Tahun
    $('#filterBulan, #filterTahun').on('change', function() {
        datatableIkpa.ajax.reload();
    });
});
</script>

<!-- Tambah Data -->
<script>
$('#btnTambah').on('click', function(e) {
    e.preventDefault();
    $.ajax({
        url: "{{ route('ikpa-score.create') }}",
        method: "GET",
        success: function(response) {
            $('#modal-div').html("");
            if (response.status == 'success') {
                $('#modal-div').html(response.msg);
            } else {
                Swal.fire({
                    title: response.msg,
                    icon:'error'
                })
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
});
</script>

<!-- Edit Modal -->
<script>
function showModalUpdate(data){
    let url = "{{ route('ikpa-score.edit', ':id') }}".replace(':id', data)
    $.ajax({
        url: url,
        method: "GET",
        success: function(response) {
            $('#modal-div').html("");
            if (response.status == 'success') {
                $('#modal-div').html(response.msg);
            } else {
                Swal.fire({
                    title: response.msg,
                    icon: 'error',
                    confirmButtonText: "Oke"
                })
            }
        },
        error: function() {
            Swal.fire({
                title: 'Failed Show Form Update',
                icon: 'error',
                confirmButtonText: "Oke"
            })
        }
    });
}
</script>

<!-- Delete -->
<script>
function deleteData(data){
    Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "Menghapus Data",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            let url = "{{ route('ikpa-score.destroy', ':id') }}".replace(':id', data)
            $.ajax({
                url: url,
                method: "DELETE",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.status == 'success') {
                        Swal.fire({
                            title: response.msg,
                            icon: 'success',
                            confirmButtonText: "Oke"
                        }).then(function() {
                            $('#kt_table').DataTable().ajax.reload();
                        });
                    } else {
                        Swal.fire({
                            title: response.msg,
                            icon: 'error',
                            confirmButtonText: "Oke"
                        })
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Failed, Server Error',
                        icon: 'error',
                        confirmButtonText: "Oke"
                    })
                }
            });
        }
    })
}
</script>

@endsection
