<!DOCTYPE html>
<html lang="en">

<head>
	<title>
		@hasSection ('title')
		@yield('title') - 
		@endif
        Kalteng Dashboard
	</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="shortcut icon" href="{{ asset('assets/brand/logo/logokumham.png') }}" />
	<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="{{ asset('assets/js/lightbox2/lightbox.min.css') }}">
	@yield('styles')
</head>

<body body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
	<div class="page-loader flex-column bg-dark bg-opacity-25">
		<span class="spinner-border text-primary" role="status"></span>
		<span class="text-gray-800 fs-6 fw-semibold mt-5">Loading...</span>
	</div>

	<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
		<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
			@include('components.header')
			<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
				@include('components.sidebar')
				<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
					<div class="d-flex flex-column flex-column-fluid">
						@yield('toolbar')
						<div id="kt_app_content" class="app-content flex-column-fluid">
							<div id="kt_app_content_container" class="app-container container-fluid">
								@yield('content')
							</div>
						</div>
					</div>
					@include('components.footer')
				</div>
			</div>
		</div>
	</div>
	<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
		<i class="ki-duotone ki-arrow-up">
			<span class="path1"></span>
			<span class="path2"></span>
		</i>
	</div>

	<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
	<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
	<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
	<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
	<script src="{{ asset('assets/plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
	<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
	<script src="{{ asset('assets/plugins/custom/fslightbox/fslightbox.bundle.js') }}"></script>
	
	<script src="{{ asset('assets/js/lightbox2/lightbox.min.js') }}"></script>

	<script>
		function loadingScreen(){
			const loadingEl = document.createElement("div");
			document.body.prepend(loadingEl);
			loadingEl.classList.add("page-loader");
			loadingEl.classList.add("flex-column");
			loadingEl.classList.add("bg-dark");
			loadingEl.classList.add("bg-opacity-25");
			loadingEl.innerHTML = `
				<span class="spinner-border text-primary" role="status"></span>
				<span class="text-gray-800 fs-6 fw-semibold mt-5">Loading...</span>
			`;

			KTApp.showPageLoading();

		}


		function loadingScreenModal(){
			const loadingEl = document.createElement("div");
			document.body.prepend(loadingEl);

			loadingEl.innerHTML = `
				<span class="spinner-border text-primary" role="status"></span>
				<span class="text-gray-800 fs-6 fw-semibold mt-5">Loading...</span>
			`;
			KTApp.showPageLoading();

		}
		
	</script>
	@stack('scripts')
	@yield('scripts')
</body>

</html>