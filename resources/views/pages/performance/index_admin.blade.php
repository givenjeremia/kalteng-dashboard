@extends('layouts.base')
@section('title','Performance Admin')

@section('toolbar')
@include('components/toolbar',['title' => 'Performance Admin'])
@endsection

@section('content')


<div class="card card-flush">
    <div class="card-body">
        <table class="table table-bordered align-middle">
            <thead class="table-secondary">
                <tr class="fw-semibold fs-6 text-gray-800">
                    <th>DIVISI</th>
                    @foreach($file_categories as $category)
                        <th>{{ strtoupper($category->title) }}</th>
                    @endforeach
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departements as $dept)
                    @php
                        // ambil performance milik departement ini
                        $performance = $performances->firstWhere('departement_id', $dept->pkid);
        
                        $isComplete = true;
                    @endphp
                    <tr>
                        <td class="fw-bold">{{ strtoupper($dept->title) }}</td>
                        @foreach($file_categories as $category)
                            @php
                                $file = $performance?->getMedia('files')
                                    ->first(fn($media) => $media->getCustomProperty('file_category_id') == $category->pkid);
                                if (!$file) $isComplete = false;
                            @endphp
                            <td class="text-center">
                                @if($file)
                                    <div class="mt-3">
                                        <a href="{{ $file->getUrl() }}" target="_blank" class="badge bg-success text-white">
                                            Lihat File
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            Update terakhir: {{ $file->updated_at->format('d M Y H:i') }}
                                        </small>
                                    </div>
                                @else
                                    Belum Ada
                                @endif
                            </td>
                        @endforeach
                        <td class="fw-bold text-center {{ $isComplete ? 'text-success' : 'text-danger' }}">
                            {{ $isComplete ? 'LENGKAP' : 'BELUM LENGKAP' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
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