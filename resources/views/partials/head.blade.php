<nav class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>

        <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1 ml-2 text-dark">
            Welcome back, {{ auth()->user()->name }}
        </h4>

        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item">
                <h4 class="mb-0 font-weight-bold d-none d-xl-block text-muted">
                    <i class="mdi mdi-calendar-clock mr-1"></i>
                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y H:i') }}
                </h4>
            </li>
        </ul>

        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>

    <div class="navbar-menu-wrapper navbar-search-wrapper d-none d-lg-flex align-items-center">
        <ul class="navbar-nav mr-lg-2"></ul>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                    <i class="mdi mdi-account-circle text-primary" style="font-size: 30px"></i>
                    <span class="nav-profile-name font-weight-bold ml-2">{{ auth()->user()->name }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right navbar-dropdown shadow-sm" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="javascript:void(0);"
                       onclick="document.getElementById('logout-form').submit();">
                        <i class="mdi mdi-logout text-danger"></i>
                        <span class="text-danger">Logout</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
