<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BIOS Track') - Biosistem Tracking Tugas Akhir</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #1a3c6e;
            --secondary: #2a9d8f;
            --accent: #e76f51;
            --light-bg: #f8f9fa;
        }

        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary) 0%, #0f2448 100%);
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.78);
            padding: 10px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.2s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        .sidebar .nav-link i {
            width: 24px;
        }

        .sidebar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.2rem;
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        .navbar-top {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 10px 24px;
        }

        .page-header {
            background: #fff;
            padding: 20px 24px;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 24px;
        }

        .page-header h4 {
            margin: 0;
            color: var(--primary);
            font-weight: 700;
        }

        .content-area {
            padding: 0 24px 24px;
        }

        .card {
            border: none;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
            border-radius: 12px;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            padding: 15px 20px;
        }

        .stat-card {
            border-radius: 12px;
            padding: 20px;
            color: #fff;
        }

        .badge-menunggu {
            background: #f39c12;
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }

        .step-timeline {
            position: relative;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .step-dot {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            flex-shrink: 0;
            margin-right: 12px;
        }

        .step-line {
            position: absolute;
            left: 18px;
            top: 36px;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .progress-stage {
            background: #fff;
            border-radius: 10px;
            padding: 12px 16px;
            border-left: 4px solid;
            margin-bottom: 8px;
        }

        .progress-stage.proposal {
            border-color: #007bff;
        }

        .progress-stage.seminar_hasil {
            border-color: #28a745;
        }

        .progress-stage.laporan_skripsi {
            border-color: #dc3545;
        }
    </style>
    @stack('styles')
</head>

<body>
    @auth
        <!-- Sidebar -->
        <nav class="sidebar d-flex flex-column" id="sidebar">
            <div class="sidebar-brand d-flex align-items-center gap-2">
                <i class="bi bi-mortarboard-fill fs-4" style="color:#f0c040"></i>
                <div>
                    <div>Institut Teknologi Sumatera<br>
                    BIOS Track</div>
                    <small style="font-size:11px; opacity:0.7">Biosistem Tracking Tugas Akhir <br>Program studi Teknik Biosistem</small>
                </div>
            </div>
            <div class="p-3">
                <div class="d-flex align-items-center gap-2 p-2 rounded" style="background:rgba(255,255,255,0.1)">
                    <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center"
                        style="width:36px;height:36px;font-weight:700;font-size:14px;color:#333">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="color:#fff;font-size:13px;font-weight:600">{{ Str::limit(Auth::user()->name, 18) }}
                        </div>
                        <div style="color:rgba(255,255,255,0.6);font-size:11px">{{ ucfirst(Auth::user()->role) }}</div>
                    </div>
                </div>
            </div>
            <ul class="nav flex-column flex-grow-1 pb-3">
                <li class="mb-2">
                    <a href="{{ route('profile') }}" class="nav-link"><i class="bi bi-person-fill"></i> Profil</a>
                </li>
                @if (Auth::user()->role === 'mahasiswa')
                    <li class="nav-item">
                        <a href="{{ route('mahasiswa.dashboard') }}" class="nav-link @active(['mahasiswa/dashboard'])"><i
                                class="bi bi-grid-fill"></i> Dashboard</a>
                    </li>
                    <li>
                        <div
                            style="color:rgba(255,255,255,0.4);font-size:11px;padding:10px 20px 4px;text-transform:uppercase;letter-spacing:1px">
                            Bimbingan</div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mahasiswa.bimbingan.index') }}" class="nav-link @active(['mahasiswa/bimbingan'])"><i
                                class="bi bi-journal-bookmark-fill"></i> Jadwal Bimbingan</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mahasiswa.bimbingan.riwayat') }}" class="nav-link @active(['mahasiswa/bimbingan/riwayat/all'])"><i
                                class="bi bi-clock-history"></i> Riwayat Bimbingan</a>
                    </li>
                    <li>
                        <div
                            style="color:rgba(255,255,255,0.4);font-size:11px;padding:10px 20px 4px;text-transform:uppercase;letter-spacing:1px">
                            Ujian</div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mahasiswa.ujian.index') }}" class="nav-link @active(['mahasiswa/ujian'])"><i
                                class="bi bi-file-earmark-check-fill"></i> Jadwal Ujian</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mahasiswa.ujian.riwayat') }}" class="nav-link @active(['mahasiswa/ujian/riwayat/all'])"><i class="bi bi-archive-fill"></i>
                            Riwayat Ujian</a>
                    </li>
                @elseif(Auth::user()->role === 'dosen' || (Auth::user()->role === 'kaprodi' && request()->is('dosen/*')))
                    <li class="nav-item">
                        <a href="{{ route('dosen.dashboard') }}" class="nav-link"><i class="bi bi-grid-fill"></i> Dashboard
                            Dosen</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dosen.bimbingan.index') }}" class="nav-link"><i class="bi bi-journal-check"></i>
                            Bimbingan</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dosen.ujian.index') }}" class="nav-link"><i
                                class="bi bi-file-earmark-check-fill"></i> Ujian</a>
                    </li>
                @elseif(Auth::user()->role === 'kaprodi')
                    <li class="nav-item">
                        <a href="{{ route('kaprodi.dashboard') }}" class="nav-link"><i class="bi bi-grid-fill"></i>
                            Dashboard</a>
                    </li>
                    <li>
                        <div
                            style="color:rgba(255,255,255,0.4);font-size:11px;padding:10px 20px 4px;text-transform:uppercase;letter-spacing:1px">
                            Monitoring</div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kaprodi.mahasiswa.index') }}" class="nav-link"><i class="bi bi-people-fill"></i>
                            Data Mahasiswa</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kaprodi.bimbingan.index') }}" class="nav-link"><i
                                class="bi bi-journal-bookmark-fill"></i> Semua Bimbingan</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('kaprodi.ujian.index') }}" class="nav-link"><i
                                class="bi bi-file-earmark-check-fill"></i> Persetujuan Ujian</a>
                    </li>
                    <hr style="border-color:rgba(255,255,255,0.2);margin:8px 16px">
                    <li class="nav-item">
                        <a href="{{ route('dosen.dashboard') }}" class="nav-link"><i class="bi bi-person-badge-fill"></i>
                            Panel Dosen</a>
                    </li>
                @elseif(Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i>
                            Dashboard</a>
                    </li>
                    <li>
                        <div
                            style="color:rgba(255,255,255,0.4);font-size:11px;padding:10px 20px 4px;text-transform:uppercase;letter-spacing:1px">
                            Manajemen</div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link"><i class="bi bi-people-fill"></i>
                            Kelola Pengguna</a>
                    </li>
                    <li>
                        <div
                            style="color:rgba(255,255,255,0.4);font-size:11px;padding:10px 20px 4px;text-transform:uppercase;letter-spacing:1px">
                            Monitoring (Baca Saja)</div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.mahasiswa.index') }}" class="nav-link"><i class="bi bi-people-fill"></i>
                            Data Mahasiswa</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.bimbingan.index') }}" class="nav-link"><i class="bi bi-journal-bookmark-fill"></i>
                            Semua Bimbingan</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.ujian.index') }}" class="nav-link"><i class="bi bi-file-earmark-check-fill"></i>
                            Semua Ujian</a>
                    </li>
                @endif
                <li class="mt-auto">
                    <hr style="border-color:rgba(255,255,255,0.2);margin:8px 16px">
                    <a href="{{ route('notifications.index') }}" class="nav-link position-relative">
                        <i class="bi bi-bell-fill"></i> Notifikasi
                        <span class="badge bg-danger notification-count" id="notif-count"
                            style="display:none;font-size:10px;"></span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-start w-100"
                            style="color:rgba(255,100,100,0.9)"><i class="bi bi-box-arrow-right"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <div class="navbar-top d-flex align-items-center justify-content-between">
                <button class="btn btn-sm d-md-none"
                    onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('notifications.index') }}" class="btn btn-light btn-sm position-relative">
                        <i class="bi bi-bell fs-5"></i>
                        <span class="badge bg-danger rounded-pill position-absolute notification-count" id="notif-badge"
                            style="top:-4px;right:-4px;font-size:10px;display:none;"></span>
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center gap-2"
                            data-bs-toggle="dropdown">
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                                style="width:28px;height:28px;font-size:12px">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            {{ Str::limit(Auth::user()->name, 15) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>
                                    Profil</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i
                                            class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Page Header -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">

                    <!-- Kiri -->
                    <div>
                        <h4 class="mb-0">@yield('page-title')</h4>

                        @hasSection('breadcrumb')
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 mt-1">
                                    @yield('breadcrumb')
                                </ol>
                            </nav>
                        @endif
                    </div>

                    <!-- Kanan -->
                    <div>
                        @yield('page-actions')
                    </div>

                </div>
            </div>

            <!-- Content -->
            <div class="content-area pt-3">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Terdapat kesalahan:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    @else
        @yield('content')
    @endauth

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fetch unread notification count
        function updateNotifCount() {
            fetch('{{ route('notifications.unread') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(r => r.json())
                .then(data => {
                    const elements = document.querySelectorAll('.notification-count');
                    elements.forEach(el => {
                        if (data.count > 0) {
                            el.textContent = data.count > 99 ? '99+' : data.count;
                            el.style.display = 'inline-block';
                        } else {
                            el.style.display = 'none';
                        }
                    });
                }).catch(() => {});
        }
        @auth
        updateNotifCount();
        setInterval(updateNotifCount, 30000);
        @endauth
    </script>
    @stack('scripts')
</body>

</html>
