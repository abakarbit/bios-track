@extends('layouts.app')
@section('title', 'Portal BIOS Track')
@section('content')
<style>
    :root {
        --primary: #1a3c6e;
        --primary-light: #254f8a;
        --primary-dark: #0f2847;
        --secondary: #2a9d8f;
        --accent: #f59e0b;
        --bg: #f0f2f7;
        --card: #ffffff;
        --text: #1e293b;
        --text-2: #4b5563;
        --text-3: #9ca3af;
        --border: #e2e5ed;
        --border-light: #f0f1f5;
        --r: 10px;
        --r-lg: 14px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--bg);
        color: var(--text);
        -webkit-font-smoothing: antialiased;
        line-height: 1.5;
    }

    /* ── NAVBAR ── */
    .topbar {
        background: var(--card);
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 0;
        z-index: 100;
        transition: box-shadow 0.25s;
    }
    .topbar.scrolled { box-shadow: 0 1px 12px rgba(0,0,0,0.06); }
    .topbar-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .topbar-brand {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        text-decoration: none;
    }
    .topbar-logo {
        width: 34px; height: 34px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 8px;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 0.95rem;
        flex-shrink: 0;
    }
    .topbar-name {
        font-weight: 700;
        font-size: 1rem;
        color: var(--primary);
        line-height: 1.2;
    }
    .topbar-name small {
        display: block;
        font-size: 0.6rem;
        font-weight: 500;
        color: var(--text-3);
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }
    .topbar-right {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }
    .topbar-pill {
        display: none;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.72rem;
        color: var(--text-2);
        background: var(--bg);
        padding: 0.3rem 0.7rem;
        border-radius: 20px;
        font-weight: 500;
    }
    .topbar-pill .dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #22c55e;
        flex-shrink: 0;
    }
    .btn-nav-masuk {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.4rem 1rem;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 7px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        font-family: inherit;
    }
    .btn-nav-masuk:hover { background: var(--primary-light); }

    /* ── MAIN ── */
    .portal-main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.75rem 1.5rem 3rem;
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 1.5rem;
        align-items: start;
    }

    /* ── PANEL HEAD ── */
    .panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.85rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .panel-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .panel-title i { color: var(--secondary); }
    .panel-meta {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .panel-count {
        font-size: 0.72rem;
        font-weight: 600;
        background: var(--primary);
        color: #fff;
        padding: 0.18rem 0.55rem;
        border-radius: 5px;
    }
    .panel-updated {
        font-size: 0.7rem;
        color: var(--text-3);
    }

    /* ── TABLE ── */
    .table-wrap {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .table-scroll {
        overflow-x: auto;
        max-height: 68vh;
        overflow-y: auto;
    }
    .table-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
    .table-scroll::-webkit-scrollbar-track { background: transparent; }
    .table-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
    .table-scroll::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

    .ujian-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 820px;
    }
    .ujian-table thead {
        background: #f8f9fc;
        position: sticky;
        top: 0;
        z-index: 5;
    }
    .ujian-table th {
        padding: 0.7rem 0.85rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--text-3);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1.5px solid var(--border);
        white-space: nowrap;
    }
    .ujian-table td {
        padding: 0.75rem 0.85rem;
        font-size: 0.82rem;
        border-bottom: 1px solid var(--border-light);
        color: var(--text-2);
        vertical-align: top;
    }
    .ujian-table tbody tr { transition: background 0.15s; }
    .ujian-table tbody tr:hover { background: #fafbfd; }
    .ujian-table tbody tr:last-child td { border-bottom: none; }

    /* Jenis badge */
    .j-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.68rem;
        font-weight: 700;
        padding: 0.22rem 0.55rem;
        border-radius: 5px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        white-space: nowrap;
    }
    .j-badge.proposal     { background: #eff6ff; color: #2563eb; }
    .j-badge.komprehensif { background: #f5f3ff; color: #7c3aed; }
    .j-badge.ujian_skripsi{ background: #ecfdf5; color: #059669; }
    .j-badge.seminar       { background: #fffbeb; color: #d97706; }
    .j-badge.default       { background: #f3f4f6; color: #6b7280; }

    /* Status dot */
    .status-dot {
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        font-size: 0.65rem;
        font-weight: 600;
        color: #16a34a;
        margin-top: 0.2rem;
    }
    .status-dot::before {
        content: '';
        width: 5px; height: 5px;
        border-radius: 50%;
        background: #22c55e;
    }

    /* Mahasiswa cell */
    .m-name {
        font-weight: 600;
        color: var(--text);
        font-size: 0.83rem;
        line-height: 1.3;
    }
    .m-nim {
        font-size: 0.72rem;
        color: var(--text-3);
        margin-top: 0.1rem;
    }

    /* Jadwal cell */
    .j-date {
        font-weight: 600;
        color: var(--text);
        font-size: 0.83rem;
    }
    .j-time {
        font-size: 0.72rem;
        color: var(--text-3);
        margin-top: 0.1rem;
    }

    /* Tempat */
    .t-ruang {
        font-size: 0.82rem;
        max-width: 140px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Dosen cell */
    .d-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.25rem 0.75rem;
    }
    .d-item {
        font-size: 0.75rem;
        color: var(--text-2);
        line-height: 1.35;
    }
    .d-label {
        font-size: 0.6rem;
        font-weight: 600;
        color: var(--text-3);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        display: block;
        margin-bottom: 0.02rem;
    }

    /* Download btn */
    .btn-dl {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.32rem 0.6rem;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 0.68rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
        font-family: inherit;
    }
    .btn-dl:hover { background: var(--primary-light); box-shadow: 0 2px 8px rgba(26,60,110,0.18); }
    .btn-dl.empty {
        background: none;
        color: var(--text-3);
        cursor: default;
        pointer-events: none;
    }
    .btn-dl.empty:hover { box-shadow: none; }

    /* Empty state */
    .s-empty {
        padding: 3.5rem 2rem;
        text-align: center;
    }
    .s-empty-icon {
        width: 56px; height: 56px;
        border-radius: 14px;
        background: var(--bg);
        display: grid;
        place-items: center;
        margin: 0 auto 1rem;
        color: var(--text-3);
        font-size: 1.5rem;
    }
    .s-empty h4 {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 0.3rem;
    }
    .s-empty p {
        font-size: 0.82rem;
        color: var(--text-3);
        max-width: 280px;
        margin: 0 auto;
        line-height: 1.5;
    }

    /* ── LOGIN PANEL ── */
    .login-panel {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        overflow: hidden;
        position: sticky;
        top: 74px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .login-head {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 60%, var(--secondary) 100%);
        padding: 1.4rem 1.5rem;
        color: #fff;
    }
    .login-head h3 {
        font-size: 0.92rem;
        font-weight: 700;
        margin-bottom: 0.1rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .login-head p {
        font-size: 0.74rem;
        opacity: 0.55;
        margin: 0;
    }
    .login-body { padding: 1.4rem 1.5rem; }

    .fg { margin-bottom: 0.85rem; }
    .fg label {
        display: block;
        font-size: 0.76rem;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 0.28rem;
    }
    .fg-input { position: relative; }
    .fg-input .fi {
        position: absolute;
        left: 0.65rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-3);
        font-size: 0.85rem;
        pointer-events: none;
        transition: color 0.2s;
    }
    .fg-input input {
        width: 100%;
        padding: 0.58rem 0.7rem 0.58rem 2.15rem;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: 0.84rem;
        color: var(--text);
        background: #fafbfd;
        outline: none;
        transition: all 0.2s;
        font-family: inherit;
    }
    .fg-input input::placeholder { color: var(--text-3); }
    .fg-input input:focus {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(26,60,110,0.07);
    }
    .fg-input input:focus + .fi,
    .fg-input input:focus ~ .fi { color: var(--primary); }
    .fg-input input.is-invalid {
        border-color: #ef4444;
        background: #fef2f2;
    }
    .fg-err { font-size: 0.72rem; color: #ef4444; margin-top: 0.22rem; }
    .pw-wrap input { padding-right: 2.4rem; }
    .pw-btn {
        position: absolute;
        right: 0.4rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-3);
        cursor: pointer;
        padding: 0.2rem;
        font-size: 0.88rem;
        transition: color 0.2s;
    }
    .pw-btn:hover { color: var(--text-2); }

    .fg-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.1rem;
    }
    .fg-check {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    .fg-check input { width: 14px; height: 14px; accent-color: var(--primary); cursor: pointer; }
    .fg-check label { font-size: 0.76rem; color: var(--text-2); cursor: pointer; }
    .fg-link {
        font-size: 0.73rem;
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }
    .fg-link:hover { text-decoration: underline; }

    .btn-login {
        width: 100%;
        padding: 0.62rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 0.86rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        font-family: inherit;
    }
    .btn-login:hover {
        box-shadow: 0 4px 14px rgba(26,60,110,0.28);
        transform: translateY(-1px);
    }
    .btn-login:active { transform: translateY(0); }

    .login-sep {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin: 1.1rem 0;
    }
    .login-sep::before,
    .login-sep::after { content: ''; flex: 1; height: 1px; background: var(--border); }
    .login-sep span { font-size: 0.7rem; color: var(--text-3); }

    .login-alt {
        text-align: center;
        font-size: 0.78rem;
        color: var(--text-2);
        line-height: 1.6;
    }
    .login-alt a {
        color: var(--primary);
        font-weight: 600;
        text-decoration: none;
    }
    .login-alt a:hover { text-decoration: underline; }

    .login-features {
        margin-top: 1.25rem;
        padding-top: 1.1rem;
        border-top: 1px solid var(--border-light);
    }
    .login-features h4 {
        font-size: 0.68rem;
        font-weight: 700;
        color: var(--text-3);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.6rem;
    }
    .lf-list { list-style: none; }
    .lf-list li {
        font-size: 0.76rem;
        color: var(--text-2);
        padding: 0.22rem 0;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .lf-list li i {
        font-size: 0.65rem;
        color: var(--secondary);
        flex-shrink: 0;
    }

    /* ── FOOTER DOWNLOADS ── */
    .portal-dl {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 70%, #1d5a9e 100%);
        color: #fff;
        padding: 2.5rem 0 2rem;
    }
    .portal-dl-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    .portal-dl-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .portal-dl-title {
        font-size: 1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    .portal-dl-title i { color: var(--accent); }
    .portal-dl-sub {
        font-size: 0.76rem;
        opacity: 0.55;
        margin-top: 0.15rem;
    }
    .portal-dl-badge {
        font-size: 0.68rem;
        font-weight: 600;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.18);
        color: rgba(255,255,255,0.85);
        padding: 0.28rem 0.7rem;
        border-radius: 20px;
        white-space: nowrap;
    }
    .portal-dl-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.9rem;
    }
    .dl-card {
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 10px;
        padding: 1.1rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.55rem;
        transition: background 0.2s, border-color 0.2s, transform 0.2s;
    }
    .dl-card:hover {
        background: rgba(255,255,255,0.13);
        border-color: rgba(255,255,255,0.25);
        transform: translateY(-2px);
    }
    .dl-card-icon {
        width: 38px; height: 38px;
        border-radius: 8px;
        background: rgba(255,255,255,0.12);
        display: grid;
        place-items: center;
        font-size: 1.05rem;
        color: var(--accent);
        flex-shrink: 0;
    }
    .dl-card-label {
        font-size: 0.78rem;
        font-weight: 600;
        line-height: 1.4;
        color: rgba(255,255,255,0.92);
        flex: 1;
    }
    .dl-card-note {
        font-size: 0.68rem;
        color: rgba(255,255,255,0.45);
        line-height: 1.4;
    }
    .dl-card-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.38rem 0.8rem;
        background: var(--accent);
        color: #fff;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        align-self: flex-start;
        margin-top: auto;
    }
    .dl-card-btn:hover {
        background: #e08a00;
        box-shadow: 0 3px 10px rgba(245,158,11,0.35);
        color: #fff;
    }

    /* ── FOOTER ── */
    .portal-foot {
        border-top: 1px solid var(--border);
        background: var(--card);
    }
    .portal-foot-inner {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0.85rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.4rem;
    }
    .portal-foot p {
        font-size: 0.72rem;
        color: var(--text-3);
        margin: 0;
    }

    /* ── ROW FADE-IN ── */
    @keyframes rowIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .row-anim {
        animation: rowIn 0.35s ease both;
    }

    /* ── RESPONSIVE ── */

    /* Tablet */
    @media (max-width: 900px) {
        .portal-main { grid-template-columns: 1fr; }
        .login-panel {
            position: static;
            max-width: 400px;
            justify-self: center;
            width: 100%;
        }
        .topbar-pill { display: flex; }
        .table-scroll { max-height: 60vh; }
    }

    /* Mobile: table → card */
    @media (max-width: 680px) {
        .portal-main { padding: 1.25rem 1rem 2rem; }
        .panel-head { flex-direction: column; align-items: flex-start; gap: 0.35rem; }
        .table-scroll { max-height: none; overflow-x: visible; }
        .ujian-table { min-width: 0; }

        .ujian-table thead { display: none; }
        .ujian-table tbody tr {
            display: block;
            margin-bottom: 0.75rem;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--r);
            padding: 0.85rem 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .ujian-table tbody tr:hover { background: var(--card); }
        .ujian-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.4rem 0;
            border-bottom: 1px solid var(--border-light);
            font-size: 0.8rem;
        }
        .ujian-table tbody td:last-child { border-bottom: none; }
        .ujian-table tbody td::before {
            content: attr(data-label);
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--text-3);
            text-transform: uppercase;
            letter-spacing: 0.03em;
            white-space: nowrap;
            flex-shrink: 0;
            padding-top: 0.05rem;
        }

        /* Card-specific overrides */
        .ujian-table td[data-label="Jenis"] { flex-direction: row; }
        .ujian-table td[data-label="Mahasiswa"] { flex-direction: column; gap: 0.1rem; }
        .ujian-table td[data-label="Tim Dosen"] { flex-direction: column; gap: 0.25rem; }
        .ujian-table td[data-label="Dokumen"] { justify-content: flex-end; }

        .d-grid { grid-template-columns: 1fr; gap: 0.15rem; }

        .login-body { padding: 1.25rem; }
        .portal-foot-inner { flex-direction: column; text-align: center; }
        .portal-dl-grid { grid-template-columns: repeat(2, 1fr); }
        .portal-dl-head { flex-direction: column; align-items: flex-start; }
    }

    @media (max-width: 400px) {
        .topbar-inner { padding: 0 1rem; }
        .topbar-pill { display: none; }
        .ujian-table tbody tr { padding: 0.75rem 0.85rem; }
        .portal-dl-grid { grid-template-columns: 1fr; }
    }
</style>

<!-- NAVBAR -->
<nav class="topbar" id="topbar">
    <div class="topbar-inner">
        <a href="/" class="topbar-brand">
            <div class="topbar-logo"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="topbar-name">
                BIOS Track
                <small>Biosistem Tracking Tugas Akhir</small>
            </div>
        </a>
        <div class="topbar-right">
            <div class="topbar-pill">
                <span class="dot"></span>
                Semester Genap 2024/2025
            </div>
            <a href="#login" class="btn-nav-masuk">
                <i class="bi bi-box-arrow-in-right"></i> Masuk
            </a>
        </div>
    </div>
</nav>

<!-- MAIN -->
<main class="portal-main">

    <!-- JADWAL UJIAN -->
    <div class="schedule-panel">
        <div class="panel-head">
            <h2 class="panel-title">
                <i class="bi bi-calendar2-event-fill"></i>
                Jadwal Ujian Mendatang
            </h2>
            <div class="panel-meta">
                <span class="panel-count">{{ $ujians->count() }} terjadwal</span>
                <span class="panel-updated">
                    <i class="bi bi-clock-history"></i> Diperbarui {{ \Carbon\Carbon::now()->format('d M Y') }}
                </span>
            </div>
        </div>

        @if($ujians->isEmpty())
            <div class="table-wrap">
                <div class="s-empty">
                    <div class="s-empty-icon"><i class="bi bi-inbox"></i></div>
                    <h4>Belum Ada Jadwal</h4>
                    <p>Informasi jadwal ujian akan ditampilkan setelah dipublikasikan oleh administrasi.</p>
                </div>
            </div>
        @else
            <div class="table-wrap">
                <div class="table-scroll">
                    <table class="ujian-table">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Mahasiswa</th>
                                <th>Prodi</th>
                                <th>Jadwal</th>
                                <th>Tempat</th>
                                <th>Tim Dosen</th>
                                <th style="text-align:center">Dokumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ujians as $idx => $ujian)
                                @php
                                    $jenisSlug = $ujian->jenis_ujian ?? 'default';
                                    $jenisLabel = match($jenisSlug) {
                                        'proposal'       => 'PROPOSAL',
                                        'laporan_skripsi'  => 'SIDANG AKHIR',
                                        'seminar_hasil'  => 'SEMINAR HASIL',
                                        default          => 'UJIAN'
                                    };
                                    $d = \Carbon\Carbon::parse($ujian->tanggal_ujian);
                                    $dlUrl = match($jenisSlug) {
                                        'proposal'      => asset('storage/file/ujian_proposal.zip'),
                                        'seminar_hasil' => asset('storage/file/seminar_hasil.zip'),
                                        'laporan_skripsi' => asset('storage/file/ujian_sidang_akhir.zip'),
                                        default         => null
                                    };
                                @endphp
                                <tr class="row-anim" style="animation-delay: {{ $idx * 0.05 }}s">
                                    <td data-label="Jenis">
                                        <span class="j-badge {{ $jenisSlug }}">
                                            <i class="bi bi-mortarboard-fill"></i> {{ $jenisLabel }}
                                        </span>
                                    </td>
                                    <td data-label="Mahasiswa">
                                        <div class="m-name">{{ $ujian->mahasiswa->name }}</div>
                                        <div class="m-nim">{{ $ujian->mahasiswa->nim }}</div>
                                    </td>
                                    <td data-label="Prodi">{{ $ujian->mahasiswa->prodi }}</td>
                                    <td data-label="Jadwal">
                                        <div class="j-date">{{ $d->format('d M Y') }}</div>
                                        <div class="j-time">{{ $d->format('H.i') }} WIB</div>
                                    </td>
                                    <td data-label="Tempat">
                                        <div class="t-ruang" title="{{ $ujian->tempat_ujian }}">
                                            {{ $ujian->tempat_ujian ?? '-' }}
                                        </div>
                                    </td>
                                    <td data-label="Tim Dosen">
                                        <div class="d-grid">
                                            <div class="d-item">
                                                <span class="d-label">Pemb. 1</span>
                                                {{ $ujian->pembimbing1->name ?? '-' }}
                                            </div>
                                            <div class="d-item">
                                                <span class="d-label">Pemb. 2</span>
                                                {{ $ujian->pembimbing2->name ?? '-' }}
                                            </div>
                                            <div class="d-item">
                                                <span class="d-label">Peng. 1</span>
                                                {{ $ujian->penguji1->name ?? '-' }}
                                            </div>
                                            <div class="d-item">
                                                <span class="d-label">Peng. 2</span>
                                                {{ $ujian->penguji2->name ?? '-' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Dokumen" style="text-align:center">
                                        @if($dlUrl)
                                            <a href="{{ $dlUrl }}" class="btn-dl" title="Download dokumen">
                                                <i class="bi bi-download"></i> Unduh
                                            </a>
                                        @else
                                            <span class="btn-dl empty">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- LOGIN -->
    <div class="login-panel" id="login">
        <div class="login-head">
            <h3><i class="bi bi-shield-lock"></i> Masuk Akun</h3>
            <p>Monitoring bimbingan & ujian skripsi</p>
        </div>
        <div class="login-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="fg">
                    <label for="email">Email</label>
                    <div class="fg-input">
                        <input type="email" name="email" id="email"
                               class="@error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="email@university.ac.id"
                               required autofocus>
                        <i class="bi bi-envelope fi"></i>
                    </div>
                    @error('email')
                        <div class="fg-err">{{ $message }}</div>
                    @enderror
                </div>
                <div class="fg">
                    <label for="password">Password</label>
                    <div class="fg-input pw-wrap">
                        <input type="password" name="password" id="pwInput"
                               placeholder="Masukkan password" required>
                        <i class="bi bi-lock fi"></i>
                        <button type="button" class="pw-btn" onclick="togglePw()" aria-label="Tampilkan password">
                            <i class="bi bi-eye" id="pwEye"></i>
                        </button>
                    </div>
                </div>
                <div class="fg-row">
                    <div class="fg-check">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Ingat saya</label>
                    </div>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="fg-link">Lupa password?</a>
                    @endif
                </div>
                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                </button>
            </form>

            <div class="login-sep"><span>atau</span></div>

            <p class="login-alt">
                Belum punya akun?<br>
                <a href="{{ route('register') }}">Daftar sebagai Mahasiswa</a>
            </p>

            <div class="login-features">
                <h4>Setelah masuk, Anda bisa</h4>
                <ul class="lf-list">
                    <li><i class="bi bi-check-circle-fill"></i> Monitoring progres bimbingan</li>
                    <li><i class="bi bi-check-circle-fill"></i> Ajukan dan pantau jadwal ujian</li>
                    <li><i class="bi bi-check-circle-fill"></i> Unduh berkas dan dokumen</li>
                    <li><i class="bi bi-check-circle-fill"></i> Lihat riwayat akademik</li>
                </ul>
            </div>
        </div>
    </div>
</main>

<!-- DOWNLOADS SECTION -->
<section class="portal-dl">
    <div class="portal-dl-inner">
        <div class="portal-dl-head">
            <div>
                <div class="portal-dl-title">
                    <i class="bi bi-cloud-arrow-down-fill"></i>
                    Unduh Dokumen &amp; Formulir
                </div>
            </div>
        </div>
        <div class="portal-dl-grid">
            <!-- 1 -->
            <div class="dl-card">
                <div class="dl-card-icon"><i class="bi bi-person-lines-fill"></i></div>
                <div class="dl-card-label">Form Pemilihan Dosen Pembimbing Tugas Akhir</div>
                <div class="dl-card-note">Formulir pengajuan & pemilihan dosen pembimbing skripsi</div>
                <a href="{{ asset('storage/file/form_pemilihan_dosen_pembimbing.docx') }}" download class="dl-card-btn">
                    <i class="bi bi-download"></i> Unduh DOCX
                </a>
            </div>
            <!-- 2 -->
            <div class="dl-card">
                <div class="dl-card-icon"><i class="bi bi-file-earmark-check-fill"></i></div>
                <div class="dl-card-label">Form Persetujuan Revisi dan Cetak Tugas Akhir</div>
                <div class="dl-card-note">Formulir persetujuan setelah revisi ujian akhir selesai</div>
                <a href="{{ asset('storage/file/form_persetujuan_revisi_cetak.docx') }}" download class="dl-card-btn">
                    <i class="bi bi-download"></i> Unduh DOCX
                </a>
            </div>
            <!-- 3 -->
            <div class="dl-card">
                <div class="dl-card-icon"><i class="bi bi-card-text"></i></div>
                <div class="dl-card-label">Kartu Kuning Sempro &amp; Semhas</div>
                <div class="dl-card-note">
                    <i class="bi bi-exclamation-circle me-1" style="color:var(--accent)"></i>
                    Print menggunakan kertas kuning tebal, ukuran Legal
                </div>
                <a href="{{ asset('storage/file/kartu_kuning_sempro_semhas.docx') }}" download class="dl-card-btn">
                    <i class="bi bi-download"></i> Unduh DOCX
                </a>
            </div>
            <!-- 4 -->
            <div class="dl-card">
                <div class="dl-card-icon"><i class="bi bi-file-earmark-word-fill"></i></div>
                <div class="dl-card-label">Templat Skripsi</div>
                <div class="dl-card-note">Templat penulisan skripsi sesuai panduan program studi</div>
                <a href="{{ asset('storage/file/Templatskripsi.pdf') }}" download class="dl-card-btn">
                    <i class="bi bi-download"></i> Unduh PDF
                </a>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="portal-foot">
    <div class="portal-foot-inner">
        <p>&copy; {{ date('Y') }} BIOS Track — Biosistem Tracking Tugas Akhir</p>
        <p>Login diperlukan untuk mengakses dashboard monitoring</p>
    </div>
</footer>

<script>
// Toggle password visibility
function togglePw() {
    const inp = document.getElementById('pwInput');
    const ico = document.getElementById('pwEye');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    ico.className = inp.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}

// Navbar shadow on scroll
const topbar = document.getElementById('topbar');
window.addEventListener('scroll', () => {
    topbar.classList.toggle('scrolled', window.scrollY > 8);
}, { passive: true });

// Smooth scroll to login
document.querySelectorAll('a[href="#login"]').forEach(a => {
    a.addEventListener('click', e => {
        e.preventDefault();
        const el = document.getElementById('login');
        const top = el.getBoundingClientRect().top + window.scrollY - 70;
        window.scrollTo({ top, behavior: 'smooth' });
    });
});
</script>
@endsection
