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
                        <label for="required" class="fs-6 fw-semibold mb-2">Departement</label>
                        <select name="departement_id" class="form-control form-select">
                            <option value="">Pilih Departements</option>

                            @foreach ($departements as $item)
                                <option value="{{ $item->pkid }}" {{ $data->departement_id == $item->pkid ? 'selected' : '' }}>{{ $item->title }}</option>
                                
                            @endforeach
                        </select>
                    </div>
                    
                    
                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Bulan</label>
                        <input type="number" class="form-control" name="bulan" value="{{ $data->bulan }}" placeholder="Tuliskan Bulan">
                    </div>

                    

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Tahun</label>
                        <input type="number" class="form-control" name="tahun" value="{{ $data->tahun }}"  placeholder="Tuliskan Tahun">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Deviation Dipa</label>
                        <input type="number" class="form-control" name="deviation_dipa" value="{{ $data->deviation_dipa }}" placeholder="Tuliskan Deviation Dipa">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Revisi Dipa</label>
                        <input type="number" class="form-control" name="revisi_dipa" value="{{ $data->revisi_dipa }}" placeholder="Tuliskan Revisi Dipa">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Penyerapan Anggaran</label>
                        <input type="number" class="form-control" name="penyerapan_anggaran" value="{{ $data->penyerapan_anggaran }}" placeholder="Tuliskan Penyerapan Anggaran">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Capaian Output</label>
                        <input type="number" class="form-control" name="capaian_output" value="{{ $data->capaian_output }}" placeholder="Tuliskan Capaian Output">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Penyelesaian Tagihan</label>
                        <input type="number" class="form-control" name="penyelesaian_tagihan" value="{{ $data->penyelesaian_tagihan }}" placeholder="Tuliskan Penyelesaian Tagihan">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Pengelolaan Up Tup</label>
                        <input type="number" class="form-control" name="pengelolaan_up_tup" value="{{ $data->pengelolaan_up_tup }}"  placeholder="Tuliskan Pengelolaan Up Tup">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Belanja Kontraktual</label>
                        <input type="number" class="form-control" name="belanja_kontraktual" value="{{ $data->belanja_kontraktual }}" placeholder="Tuliskan Belanja Kontraktual">
                    </div>

                    <div class="fv-row mt-3">
                        <label for="required" class="required fs-6 fw-semibold mb-2">Nilai Ikpa</label>
                        <input type="number" class="form-control" name="nilai_ikpa" value="{{ $data->nilai_ikpa }}" placeholder="Tuliskan Nilai Ikpa">
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
                let act = "{{ route('ikpa-score.update',':id') }}".replace(':id','{{ $data->uuid }}')
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