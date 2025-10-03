<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login - Kalimantan Tengah Dashboard</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="shortcut icon" href="{{ asset('assets/brand/logo/logokumham.png') }}" />
        <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    </head>
    <body id="kt_body" class="app-blank">
        <div class="d-flex flex-column flex-root" id="kt_app_root">
            <div class="d-flex flex-column flex-lg-row flex-column-fluid">
                <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-1"
                    style="background-image: url('https://asset.kompas.com/crops/HnWQTzPb-sgK3YSjL3VNs-rEILc=/0x0:0x0/750x500/data/photo/buku/636d11c90408c.jpg')">
                    <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                        <img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20 rounded-4" src="{{ asset('assets/brand/logo/logokumham.png') }}" alt="" />
                        <h1 class="text-dark fs-2qx fw-bold text-center mb-7">Kalteng Dashboard</h1>
                    </div>
            
                </div>
                <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-2">
                    <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                        <div class="w-lg-500px p-10">
                            <form class="form w-100" action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="mb-11">
                                    <h1 class="text-dark fw-bolder mb-3">Selamat Datang!</h1>
                                    <div class="text-gray-500 fw-semibold fs-6">Welcome To Kalteng Manajement</div>
                                </div>
                                <div class="fv-row mb-8">
                                    <label for="email" class="form-label">Username / Email</label>
                                    <input type="text" placeholder="Username / Email Anda" value="{{ old('email') }}" name="email"
                                        autocomplete="off" class="form-control bg-transparent" required />
                                </div>
                                <div class="fv-row mb-3">
                                    <label for="password" class="form-label">Kata Sandi</label>
                                    <div class="input-group  mb-5">
                                        <input id="password" type="password" placeholder="Kata Sandi" name="password" autocomplete="off" class="form-control bg-transparent" />
                                        <span class="input-group-text bg-transparent " id="btnPasswordShowHidden"  status="hidden">
                                            <i class="ki-duotone ki-eye-slash text-dark fs-2">
                                                <i class="path1"></i>
                                                <i class="path2"></i>
                                                <i class="path3"></i>
                                                <i class="path4"></i>
                                            </i>
                                        </span>
                                    </div>
                                </div>
                                @error('failed_login')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                                <div class="d-grid mb-5">
                                    <button type="submit" id="submitBtn" class="btn btn-dark">
                                        Login
                                    </button>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
        <script>
            const passwordInput = document.getElementById("password");
            const btnPasswordShowHidden = document.getElementById("btnPasswordShowHidden");
            btnPasswordShowHidden.addEventListener("click", function () {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    $('#btnPasswordShowHidden').html(`
                    <i class="ki-duotone ki-eye text-dark fs-2">
                        <i class="path1"></i>
                        <i class="path2"></i>
                        <i class="path3"></i>
                    </i>`);
                } else {
                    passwordInput.type = "password";
                    $('#btnPasswordShowHidden').html(`
                    <i class="ki-duotone ki-eye-slash text-dark fs-2">
                        <i class="path1"></i>
                        <i class="path2"></i>
                        <i class="path3"></i>
                        <i class="path4"></i>
                    </i>`);
        
                }
            });
        </script>  
    </body>
</html>