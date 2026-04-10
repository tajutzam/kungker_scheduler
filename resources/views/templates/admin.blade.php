<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        {{ env('APP_NAME') }}
    </title>
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css">
    <link rel="shortcut icon" href="{{ asset('assets') }}/images/favicon.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container-scroller d-flex">
        @include('partials.sidebar')
        <!-- partial -->


        <div class="container-fluid page-body-wrapper">

            @include('partials.head')

            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                @include('partials.footer')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets') }}/vendors/js/vendor.bundle.base.js"></script>
    <script src="{{ asset('assets') }}/vendors/chart.js/Chart.min.js"></script>
    <script src="{{ asset('assets') }}/js/off-canvas.js"></script>
    <script src="{{ asset('assets') }}/js/hoverable-collapse.js"></script>
    <script src="{{ asset('assets') }}/js/template.js"></script>
    <script src="{{ asset('assets') }}/js/dashboard.js"></script>
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


    @stack('scripts')

</body>

</html>
