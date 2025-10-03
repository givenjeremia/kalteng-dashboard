@extends('layouts.base')
@section('title','Performance')

@section('toolbar')
@include('components/toolbar',['title' => 'Performance'])
@endsection

@section('content')


<div class="card card-flush">
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
                                            <a href="{{ $file->getUrl() }}" target="_blank" class="badge bg-success text-white">
                                                Lihat File
                                            </a>
                                            <br>
                                            <small class="text-muted">
                                                Update terakhir: {{ $file->updated_at->format('d M Y H:i') }}
                                            </small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if ($file)
                                        <span class="badge bg-success text-white">Lengkap</span>
                                    @else
                                        <span class="badge bg-warning text-white">Belum diunggah</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-danger">Tidak ada kategori file</td>
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


<div id="modal-div"></div>

@endsection

@section('scripts')
<script>
    $('#performance-form').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('performances.store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                if (res.status === 'success') {
                    alert(res.message);
                    location.reload()
                } else {
                    alert(res.message);
                }
            },
            error: function(xhr) {
                alert("Terjadi error: " + xhr.responseJSON?.message);
            }
        });
    });

</script>
@endsection