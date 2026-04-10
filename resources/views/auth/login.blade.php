<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css">

    <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.png" />
</head>

<body>
    <div class="container-scroller d-flex">
        <div class="container-fluid page-body-wrapper full-page-wrapper d-flex">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="{{ asset('assets') }}/images/logo.svg" alt="logo">
                            </div>
                            <h4>Hello! let's get started</h4>
                            <h6 class="font-weight-light">Sign in to continue.</h6>
                            <form class="pt-3" method="post" action="{{ route('login.post') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control form-control-lg" id="exampleInputEmail1"
                                        placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control form-control-lg"
                                        id="exampleInputPassword1" placeholder="Password">
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn"
                                        >SIGN IN</button>
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" class="form-check-input">
                                            Keep me signed in
                                        </label>
                                    </div>
                                </div>
                                <div class="text-center mt-4 font-weight-light">
                                    Don't have an account? <a href="#" class="text-primary">Contact
                                        Administrator</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <script src="{{ asset('assets') }}/vendors/js/vendor.bundle.base.js"></script>
    <script src="{{ asset('assets') }}/js/off-canvas.js"></script>
    <script src="{{ asset('assets') }}/js/hoverable-collapse.js"></script>
    <script src="{{ asset('assets') }}/js/template.js"></script>
    <!-- endinject -->


    <script src="{{ asset('assets') }}/vendors/js/vendor.bundle.base.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // 1. Success Message
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        // 2. Single Error Message (Custom Flash)
        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        // 3. Validation Errors (Laravel $errors)
        @if($errors->any())
            @php
                $allErrors = implode('<br>', $errors->all());
            @endphp
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: '{!! $allErrors !!}',
                confirmButtonColor: '#6c757d' // Warna abu-abu untuk tombol
            });
        @endif
    </script>
</body>

</html>
