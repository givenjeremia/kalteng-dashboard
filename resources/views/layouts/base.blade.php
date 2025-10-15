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
	<style>
		.bg-biru-tua{
			background-color: #1B2C5D;
		}
		.text-biru-tua{
			color: #1B2C5D;
		}
		.bg-kuning-emas{
			background-color: #FFCB05;
		}
		.text-kuning-emas{
			color: #FFCB05;
		}
		.menu .menu-item .menu-link.active {
			background-color: #FFCB05 !important;
		}
		.menu .menu-item .menu-link.active .menu-title {
			color: white !important;
		}
		.menu-item .menu-link.active .menu-icon i {
			color: white !important;
		}
		.menu-item .menu-link.active .menu-icon i .path1,
		.menu-item .menu-link.active .menu-icon i .path2,
		.menu-item .menu-link.active .menu-icon i .path3,
		.menu-item .menu-link.active .menu-icon i .path4 {
			fill: white !important;
		}

		.menu .menu-item .menu-link {
		background-color: transparent !important;
		}

		.menu .menu-item .menu-link .menu-title {
			color: white !important;
			font-weight: 500 !important;
		}

		.menu .menu-item .menu-link .menu-icon i {
			color: white !important;
		}

		.menu .menu-item .menu-link .menu-icon i .path1,
		.menu .menu-item .menu-link .menu-icon i .path2,
		.menu .menu-item .menu-link .menu-icon i .path3,
		.menu .menu-item .menu-link .menu-icon i .path4 {
			fill: white !important;
		}

		/* === HOVER (pas diarahin mouse) === */
		.menu .menu-item .menu-link:hover {
			background-color: #FFF3B0 !important; /* kuning muda */
			color: #1B2C5D !important;
		}

		.menu .menu-item .menu-link:hover .menu-title {
			color: #1B2C5D !important;
		
		}

		.menu .menu-item .menu-link:hover .menu-icon i,
		.menu .menu-item .menu-link:hover .menu-icon i .path1,
		.menu .menu-item .menu-link:hover .menu-icon i .path2,
		.menu .menu-item .menu-link:hover .menu-icon i .path3,
		.menu .menu-item .menu-link:hover .menu-icon i .path4 {
			color: white !important;
			fill: white !important;
			
		}

		html, body {
			height: 100%;
			/* width: 100%; */
			/* overflow: hidden; */
		}

		#kt_app_content {
			min-height: 0;
			/* width: 100%; */
			overflow-y: auto;
			overflow-x: hidden; /* cegah scroll horizontal */
		}

		#kt_app_content_container {
			/* width: 100%;
			max-width: 100% !important;  */
			padding-left: 1rem;
			padding-right: 1rem;
			box-sizing: border-box;
		}

		.app-container.container-fluid {
			/* max-width: 100% !important; */
			padding: 0 1rem;
		}

	</style>
	@yield('styles')
</head>

<body body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
	<div class="page-loader flex-column bg-dark bg-opacity-25">
		<span class="spinner-border text-primary" role="status"></span>
		<span class="text-gray-800 fs-6 fw-semibold mt-5">Loading...</span>
	</div>

	<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
		<div class="app-page flex-column flex-column-fluid bg-biru-tua " id="kt_app_page">
			<div class="app-wrapper flex-column flex-row-fluid " id="kt_app_wrapper">
				@include('components.sidebar')
				
				<div class="rounded-bottom-5 rounded-top-5 d-flex flex-column p-3" style="height: 100vh;">

					<div class="flex-shrink-0">
						@include('components.header')
					</div>
					<div class="flex-grow-1 d-flex flex-column overflow-hidden bg-secondary rounded-bottom-5">
						@yield('toolbar')
			
						<div id="kt_app_content" class="flex-grow-1 overflow-auto">
							<div id="kt_app_content_container" class="app-container container-fluid py-4"  style="max-width: 100% !important; overflow-x: hidden;">
								@yield('content')
							</div>
						</div>
				
						<div class="flex-shrink-0">
							@include('components.footer')
						</div>
					</div>
					

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