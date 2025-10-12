<div class="card card-flush">
    <div class="card-header">
        <h3 class="card-title">Data Performances Month {{ $bulan }} Year {{ $tahun }}</h3>
    </div>
    <div class="card-body">
        <form id="performance-form" action="{{ route('performances.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="table-responsive">
                <table class="table table-striped gy-7 gs-7">
                    <thead>
                        <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                            <th>Web Pelaporan</th>
                            <th>Unggah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($file_categories as $item)
                            @php
                                $file = $performance?->getMedia('files')->first(fn($media) => $media->getCustomProperty('file_category_id') == $item->pkid);
                            @endphp
                            <tr>
                                <td>{{ $item->title }}</td>
                                <td>
                                    <input type="file" name="files[{{ $item->pkid }}]" class="form-control"/>
                                    @if ($file)
                                        <div class="mt-3">
                                            <a class="badge bg-success text-white fs-4" data-fslightbox="lightbox-basic" href="{{ $file->getUrl() }}">
                                                Lihat File
                                            </a>
                                            <br>
                                            <small class="text-muted fs-6">
                                                Update terakhir: {{ $file->updated_at->format('d M Y H:i') }}
                                            </small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if ($file)
                                        <span class="badge bg-success text-white fs-4">Lengkap</span>
                                    @else
                                        <span class="badge bg-warning text-white fs-4">Belum diunggah</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-danger fs-4">Tidak ada kategori file</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 🔹 Tombol Submit --}}
            <div class="mt-5 text-end">
                <button type="submit" class="btn btn-primary">
                    Simpan Performance
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $('#performance-form').on('submit', function(e) {
    e.preventDefault();

    let form = this;
    let formData = new FormData(form);

    Swal.fire({
        title: 'Yakin ingin simpan data?',
        text: "Data yang sudah diupload akan menggantikan file lama (jika ada).",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('performances.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: res.message,
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Error',
                        text: xhr.responseJSON?.message || 'Ada kesalahan server',
                    });
                }
            });
        }
    });
});


</script>

<script src="{{ asset('assets/plugins/custom/fslightbox/fslightbox.bundle.js') }}"></script>
