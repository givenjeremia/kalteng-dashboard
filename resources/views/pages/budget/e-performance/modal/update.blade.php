<form id="update_form">
    @method('put')
    <div class="modal fade" id="kt_modal_update" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content rounded">
                <div class="modal-body scroll-y ">
                    <div class="fv-row">
                        <div class="d-flex align-content-between flex-wrap flex-grow-1 mt-n2 mt-lg-n1">
                            <div class="text-start d-flex flex-column flex-grow-1 my-lg-0 my-2 pe-3 py-lg-0">
                                <h3 class="modal-title pt-2">Update Data</h3>
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
                        <label for="required" class="required fs-6 fw-semibold mb-2">Sasaran</label>
                        <input type="text" class="form-control" name="sasaran" placeholder="Tuliskan Sasaran" value="{{ $data->sasaran }}">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Indikator</label>
                        <input type="text" class="form-control" name="indikator" placeholder="Tuliskan Indikator" value="{{ $data->indikator }}">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Target</label>
                        <input type="number" class="form-control" name="target" placeholder="Tuliskan Target" value="{{ $data->target }}">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Tercapai</label>
                        <input type="number" class="form-control" name="tercapai" placeholder="Tuliskan Tercapai" value="{{ $data->tercapai }}">
                    </div>


                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Persentase Capaian</label>
                        <input type="number" class="form-control" name="persentase_capaian" placeholder="Tuliskan Tercapai" value="{{ $data->persentase_capaian }}">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Satuan</label>
                        <input type="text" class="form-control" name="satuan" placeholder="Tuliskan Satuan" value="{{ $data->satuan }}">
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
        $('#kt_modal_update').modal('show');
    });

    $('#btn-simpan').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Ubah Data"
            , text: "Apakah Anda Yakin Ingin Mengubah Data?"
            , icon: 'warning'
            , target: document.getElementById('content')
            , reverseButtons: true
            , confirmButtonText: "Yes"
            , showCancelButton: true
            , cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                let act = "{{ route('e-performance.update',':id') }}".replace(':id','{{ $data->uuid }}')
                let form_data = new FormData(document.querySelector("#update_form"));
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
                                $('#kt_modal_update').modal('hide');
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