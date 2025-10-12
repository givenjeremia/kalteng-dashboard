@extends('layouts.base')
@section('title','Budget - E-Monev')

@section('toolbar')
@include('components/toolbar',['title' => 'Budget/E-Monev'])
@endsection

@section('content')

<div class="card card-flush">
    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" data-kt-filter="search" class="form-control form-control-solid w-400px ps-12" placeholder="Search" />
            </div>
        </div>
        <div class="card-toolbar">
            <a href="#" id="btnTambah" class="btn btn-dark">Tambah</a>
        </div>

    </div>
    <div class="card-body pt-0">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table">
        </table>
    </div>
</div>

<div id="modal-div"></div>



@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        var datatableEmonev = $('#kt_table').DataTable({
            initComplete: function() {
                $('#kt_table thead').addClass('bg-light-secondary fw-bold');
            },
            columnDefs: [{ defaultContent: "-", targets: "_all" }],
            processing: true,
            serverSide: true,
            ajax: "{{ route('e-monev.index') }}",
            columns: [
                { data: 'No', name: 'No', title: 'No', className: 'px-5 text-nowrap' },
                { data: 'Departement', name: 'Departement', title: 'Departement', className: 'text-nowrap' },
                { data: 'Bulan', name: 'Bulan', title: 'Bulan', className: 'text-nowrap' },
                { data: 'Tahun', name: 'Tahun', title: 'Tahun', className: 'text-nowrap' },
                { data: 'Anggaran', name: 'Anggaran', title: 'Anggaran', className: 'text-nowrap' },
                { data: 'Fisik', name: 'Fisik', title: 'Fisik', className: 'text-nowrap' },
                { data: 'GAP', name: 'GAP', title: 'GAP', className: 'text-nowrap' },
                { data: 'Kinerja', name: 'Kinerja', title: 'Kinerja Satker', className: 'text-nowrap' },
                { data: 'Keterangan', name: 'Keterangan', title: 'Keterangan', className: 'text-nowrap' },
                { data: 'Action', name: 'Action', title: 'Action', className: 'px-5 text-nowrap' }
            ]
        });
    
        const filterSearch = document.querySelector('[data-kt-filter="search-emonev"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatableEmonev.search(e.target.value).draw();
        });
    });
    </script>
    
<script>
    $('#btnTambah').on('click',function (e){
        e.preventDefault();
        $.ajax({
            url: "{{ route('e-monev.create') }}"
            , method: "GET"
            , success: function(response) {
                $('#modal-div').html("");
                if (response.status == 'success') {
                    $('#modal-div').html(response.msg);
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
    })
</script>
<script>
    function showModalUpdate(data){
        let url = "{{ route('e-monev.edit', ':id') }}".replace(':id', data)
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
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Failed Show Form Update',
                    icon: 'error',
                    confirmButtonText: "Oke"
                })
            }
        });
    }
</script>
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
                let url = "{{ route('e-monev.destroy', ':id') }}".replace(':id', data)
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
                            }).then(function(result) {
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
                    error: function(xhr, status, error) {
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