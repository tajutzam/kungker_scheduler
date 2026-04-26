<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-flex align-items-center justify-content-center py-4 border-bottom border-secondary mb-2">
        <a class="sidebar-brand brand-logo text-center" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets') }}/logo.jpeg" alt="logo" style="width: 100px; height: auto;">
            <p class="text-white small font-weight-bold mt-2 mb-0" style="letter-spacing: 1px; line-height: 1.2;">
                DPRD KAB.<br>SIDOARJO
            </p>
        </a>
    </div>

    <ul class="nav">
        <li class="nav-item sidebar-category">
            <p>Sistem Penjadwalan Kunker</p>
            <span></span>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{route('admin.dashboard') }}">
                <i class="mdi mdi-view-quilt menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        {{-- MENU ADMIN --}}
        @if(auth()->user()->role == 'admin')
        <li class="nav-item sidebar-category">
            <p>Manajemen Data</p>
            <span></span>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#master-data" aria-expanded="false" aria-controls="master-data">
                <i class="mdi mdi-database menu-icon"></i>
                <span class="menu-title">Data Master</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="master-data">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.akd.index') }}">Data AKD</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">Data User</a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.kegiatan.index') }}">Data Kegiatans</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item sidebar-category">
            <p>Penjadwalan Admin</p>
            <span></span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.jadwal.index') }}">
                <i class="mdi mdi-calendar-text menu-icon"></i>
                <span class="menu-title">Riwayat Jadwal</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.jadwal.create') }}">
                <i class="mdi mdi-calendar-plus menu-icon"></i>
                <span class="menu-title">Susun Jadwal Baru</span>
            </a>
        </li>
        @endif

        {{-- MENU BANMUS --}}
        @if(auth()->user()->role == 'bamus')
        <li class="nav-item sidebar-category">
            <p>Menu Banmus</p>
            <span></span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('petugas.jadwal.index') }}">
                <i class="mdi mdi-check-all menu-icon"></i>
                <span class="menu-title">Persetujuan Jadwal</span>
            </a>
        </li>
        @endif

        <li class="nav-item sidebar-category">
            <p>Output</p>
            <span></span>
        </li>

        <li class="nav-item">
            @php
                $reportRoute = route('admin.jadwal.laporan')
            @endphp
            <a class="nav-link" href="{{ $reportRoute }}">
                <i class="mdi mdi-file-document-box menu-icon"></i>
                <span class="menu-title">Laporan Jadwal Final</span>
            </a>
        </li>
    </ul>
</nav>
