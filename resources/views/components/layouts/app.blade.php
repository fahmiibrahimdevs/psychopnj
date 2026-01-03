<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport" />
        <title>{{ $title }}</title>

        <!-- General CSS Files -->
        <link rel="icon" type="image/png" href="{{ asset("icons/MIDRAGON.png") }}" />
        <link rel="stylesheet" href="{{ asset("/assets/stisla/css/bootstrap.min.css") }}" />
        <link rel="stylesheet" href="{{ asset("assets/fontawesome/all.css") }}" />
        <link rel="stylesheet" href="{{ asset("assets/midragon/css/custom.css") }}" />

        @stack("general-css")

        <!-- Template CSS -->
        <link rel="stylesheet" href="{{ asset("/assets/stisla/css/style.css") }}" />
        <link rel="stylesheet" href="{{ asset("/assets/stisla/css/components.css") }}" />
        @vite(["resources/css/app.css", "resources/js/app.js"])
    </head>

    <body class="layout-3" style="font-family: 'Inter', sans-serif">
        <div id="app">
            <div class="main-wrapper container">
                <div class="navbar-bg"></div>
                <nav class="navbar navbar-expand-lg main-navbar">
                    <a href="{{ url("dashboard") }}" class="navbar-brand sidebar-gone-hide">PSYCHOROBOTIC</a>
                    <div class="navbar-nav">
                        <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar">
                            <i class="fas fa-bars"></i>
                        </a>
                    </div>
                    <form class="form-inline ml-auto"></form>
                    <ul class="navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="dropdown-title">Logged in 5 min ago</div>
                                <a href="/profile" class="dropdown-item has-icon">
                                    <i class="far fa-user"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route("logout") }}">
                                    @csrf
                                    <a
                                        href="{{ route("logout") }}"
                                        class="dropdown-item text-danger has-icon"
                                        onclick="event.preventDefault();
                                this.closest('form').submit();"
                                    >
                                        <i class="far fa-sign-out-alt"></i>
                                        Logout
                                    </a>
                                </form>
                            </div>
                        </li>
                    </ul>
                </nav>

                <nav class="navbar navbar-secondary navbar-expand-lg">
                    <div class="container">
                        <ul class="navbar-nav" style="margin-top: 0px">
                            <li class="nav-item {{ request()->is("dashboard") ? "active" : "" }}">
                                <a href="/dashboard" class="nav-link">
                                    <i class="far fa-home"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            @if (Auth::user()->hasRole("pengurus"))
                                <li class="nav-item dropdown {{ request()->is("tahun-kepengurusan") || request()->is("profil-organisasi") || request()->is("divisi") || request()->is("anggota") || request()->is("struktur-jabatan") || request()->is("open-recruitment") || request()->is("control-user") ? "active" : "" }}">
                                    <a href="#" data-toggle="dropdown" class="nav-link has-dropdown">
                                        <i class="far fa-sitemap"></i>
                                        <span>Organisasi</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item {{ request()->is("tahun-kepengurusan") ? "active" : "" }}">
                                            <a href="/tahun-kepengurusan" class="nav-link">Tahun Kepengurusan</a>
                                        </li>
                                        <li class="nav-item {{ request()->is("profil-organisasi") ? "active" : "" }}">
                                            <a href="/profil-organisasi" class="nav-link">Profil Organisasi</a>
                                        </li>
                                        <li class="nav-item {{ request()->is("divisi") ? "active" : "" }}">
                                            <a href="/divisi" class="nav-link">Divisi</a>
                                        </li>
                                        <li class="nav-item {{ request()->is("anggota") ? "active" : "" }}">
                                            <a href="/anggota" class="nav-link">Anggota</a>
                                        </li>
                                        <li class="nav-item {{ request()->is("open-recruitment") ? "active" : "" }}">
                                            <a href="/open-recruitment" class="nav-link">Open Recruitment</a>
                                        </li>
                                        <li class="nav-item {{ request()->is("control-user") ? "active" : "" }}">
                                            <a href="/control-user" class="nav-link">Control User</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown {{ request()->is("program-pembelajaran") || request()->is("pertemuan") || request()->is("presensi-kehadiran") ? "active" : "" }}">
                                    <a href="#" data-toggle="dropdown" class="nav-link has-dropdown">
                                        <i class="far fa-users-class"></i>
                                        <span>Akademik</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item {{ request()->is("program-pembelajaran") ? "active" : "" }}">
                                            <a href="/program-pembelajaran" class="nav-link">Program Pembelajaran</a>
                                        </li>
                                        <li class="nav-item {{ request()->is("pertemuan") ? "active" : "" }}">
                                            <a href="/pertemuan" class="nav-link">Pertemuan</a>
                                        </li>
                                        <li class="nav-item {{ request()->is("presensi-kehadiran") ? "active" : "" }}">
                                            <a href="/presensi-kehadiran" class="nav-link">Presensi Kehadiran</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown {{ request()->is("example") ? "active" : "" }}">
                                    <a href="#" data-toggle="dropdown" class="nav-link has-dropdown">
                                        <i class="far fa-money-bill-wave"></i>
                                        <span>Keuangan</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item {{ request()->is("example") ? "active" : "" }}">
                                            <a href="/example" class="nav-link">Ringkasan Keuangan</a>
                                        </li>
                                        <li class="nav-item {{ request()->is("example") ? "active" : "" }}">
                                            <a href="/example" class="nav-link">Transaksi</a>
                                        </li>
                                        <li class="nav-item {{ request()->is("example") ? "active" : "" }}">
                                            <a href="/example" class="nav-link">Iuran Anggota</a>
                                        </li>
                                        <li class="nav-item {{ request()->is("example") ? "active" : "" }}">
                                            <a href="/example" class="nav-link">Laporan Keuangan</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown {{ request()->is("example") ? "active" : "" }}">
                                    <a href="#" data-toggle="dropdown" class="nav-link has-dropdown">
                                        <i class="far fa-archive"></i>
                                        <span>Aset & Arsip</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item {{ request()->is("example") ? "active" : "" }}">
                                            <a href="/example" class="nav-link">Aset Organisasi</a>
                                        </li>
                                        <li class="nav-item {{ request()->is("example") ? "active" : "" }}">
                                            <a href="/example" class="nav-link">Arsip Dokumen</a>
                                        </li>
                                        <li class="nav-item {{ request()->is("example") ? "active" : "" }}">
                                            <a href="/example" class="nav-link">SK & LPJ</a>
                                        </li>
                                        <li class="nav-item {{ request()->is("example") ? "active" : "" }}">
                                            <a href="/example" class="nav-link">Surat & Proposal</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown {{ request()->is("example") ? "active" : "" }}">
                                    <a href="#" data-toggle="dropdown" class="nav-link has-dropdown">
                                        <i class="far fa-file"></i>
                                        <span>Evaluasi & Laporan</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item {{ request()->is("example") ? "active" : "" }}">
                                            <a href="/example" class="nav-link">Kritik & Saran</a>
                                        </li>
                                        <li class="nav-item {{ request()->is("example") ? "active" : "" }}">
                                            <a href="/example" class="nav-link">Rekap Evaluasi</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </div>
                </nav>

                <!-- Main Content -->
                <div class="main-content">
                    {{ $slot }}
                </div>
                <footer class="main-footer">
                    <div class="footer-left">
                        Copyright &copy; 2024
                        <div class="bullet"></div>
                        Created By
                        <a href="http://fahmiibrahimdev.tech/">Fahmi Ibrahim</a>
                    </div>
                    <div class="footer-right">1.0.1</div>
                </footer>
            </div>
        </div>

        <!-- General JS Scripts -->
        <script src="{{ asset("/assets/midragon/select2/jquery.min.js") }}"></script>
        <script src="{{ asset("assets/midragon/js/bootstrap.bundle.min.js") }}"></script>
        <script src="{{ asset("assets/midragon/js/bootstrap.min.js") }}"></script>
        <script src="{{ asset("assets/midragon/js/jquery.nicescroll.min.js") }}"></script>

        <!-- JS Libraies -->
        <script src="{{ asset("assets/midragon/js/sweetalert2@11.js") }}"></script>
        @stack("js-libraries")

        <!-- Page Specific JS File -->
        <script src="{{ asset("/assets/stisla/js/stisla.js") }}"></script>
        <script>
            window.addEventListener('swal:modal', (event) => {
                Swal.fire({
                    title: event.detail[0].message,
                    text: event.detail[0].text,
                    icon: event.detail[0].type,
                });
                $('#formDataModal').modal('hide');
            });
            window.addEventListener('swal:confirm', (event) => {
                Swal.fire({
                    title: event.detail[0].message,
                    text: event.detail[0].text,
                    icon: event.detail[0].type,
                    showCancelButton: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('delete');
                    }
                });
            });
            window.onbeforeunload = function () {
                window.scrollTo(5, 75);
            };
        </script>

        <!-- Template JS File -->
        <script src="{{ asset("/assets/stisla/js/scripts.js") }}"></script>
        <script src="{{ asset("/assets/stisla/js/custom.js") }}"></script>
        @stack("scripts")
    </body>
</html>
