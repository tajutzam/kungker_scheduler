<nav class="sidebar sidebar-offcanvas" id="sidebar">
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

        {{-- MENU ADMIN: Mengelola AKD & User (KF-01) [cite: 3, 5] --}}
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

        {{-- MENU BANMUS: Persetujuan & Monitoring (KF-05) [cite: 3, 5] --}}
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

        {{-- Laporan Jadwal Final: Akses Berbeda Route (KF-06) [cite: 5] --}}
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
