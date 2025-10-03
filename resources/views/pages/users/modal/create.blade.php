<form id="add_form">
    <div class="modal fade" id="kt_modal_add" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content rounded">
                <div class="modal-body scroll-y ">
                    <div class="fv-row">
                        <div class="d-flex align-content-between flex-wrap flex-grow-1 mt-n2 mt-lg-n1">
                            <div class="text-start d-flex flex-column flex-grow-1 my-lg-0 my-2 pe-3 py-lg-0">
                                <h3 class="modal-title pt-2">Tambah Data</h3>
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-kt-modal-action-type="close" data-bs-dismiss="modal">
                                    <i class="ki-duotone ki-cross fs-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Nama</label>
                        <input type="text" class="form-control" name="name" placeholder="Tuliskan Nama">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Tuliskan Username">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Email</label>
                        <input type="text" class="form-control" name="email" placeholder="Tuliskan Email">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Tuliskan Password">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="fs-6 fw-semibold mb-2">Departement</label>
                        <select name="departement_id" class="form-control form-select">
                            <option value="">Pilih Departements</option>

                            @foreach ($departements as $item)
                                <option value="{{ $item->pkid }}">{{ $item->title }}</option>
                                
                            @endforeach
                        </select>
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="fs-6 fw-semibold mb-2">Role</label>
                        <select name="role" class="form-control form-select">
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                
                            @endforeach
                        </select>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <button type="button" data-kt-modal-action-type="close" data-bs-dismiss="modal" class="btn btn-light-dark fw-bold me-5 w-150px">
                        Batal
                    </button>
                    <button type="submit" id="btn-simpan" class="btn btn-dark fw-bold w-150px">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    $(document).ready(function() {
        $('#kt_modal_add').modal('show');
    });

    $('#btn-simpan').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Tambah Data"
            , text: "Apakah Anda Yakin Ingin Menambahkan Data?"
            , icon: 'warning'
            , target: document.getElementById('content')
            , reverseButtons: true
            , confirmButtonText: "Yes"
            , showCancelButton: true
            , cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                let act = '{{ route("users.store") }}'
                let form_data = new FormData(document.querySelector("#add_form"));
                form_data.append('_token', '{{ csrf_token() }}')
                $.ajax({
                    url: act
                    , type: "POST"
                    , data: form_data
                    , dataType: "json"
                    , contentType: false
                    , processData: false
                    , success: function(data) {
                        if (data.status == "success") {
                            Swal.fire({
                                title: data.msg
                                , icon:'success'
                                , buttonsStyling: false
                                , showConfirmButton: false
                            }).then(function(result) {
                                $('#kt_modal_add').modal('hide');
                                $('#kt_table').DataTable().ajax.reload();
                            });

                        } else {
                            Swal.fire({
                                title:  data.msg,
                                icon:'error'
                                , buttonsStyling: false
                                , showConfirmButton: false
                            })
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            title:  textStatus,
                            text: errorThrown,
                            icon:'error', 
                            buttonsStyling: false, 
                            showConfirmButton: false
                        })
                        console.log(textStatus, errorThrown);
                    }
                })

            }
        })
    })


</script>