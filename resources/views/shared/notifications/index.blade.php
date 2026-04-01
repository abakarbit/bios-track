@extends('layouts.app')
@section('title', 'Notifikasi')
@section('page-header')@endsection
@section('page-title', 'Notifikasi')

@section('content')
<style>
    .notif-card {
        transition: all 0.2s ease;
        border-left: 4px solid transparent;
    }
    .notif-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
    }
    .notif-card.notif-unread {
        background: linear-gradient(135deg, #e0e7ff 0%, #f0f4ff 100%);
        border-left-color: #4361ee;
    }
    .notif-card.notif-unread h6,
    .notif-card.notif-unread p {
        color: inherit;
    }
    .notif-card.notif-read {
        background: #f8f9fa;
        border-left-color: #d1d5db;
    }
    .notif-card.notif-read h6 {
        color: #9ca3af;
    }
    .notif-card.notif-read p {
        color: #9ca3af;
    }
    .notif-card.notif-read:hover {
        background: #f3f4f6;
    }
    .notif-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        padding: 3px 8px;
        border-radius: 4px;
    }
    .notif-type-badge.type-success { background: #d1fae5; color: #065f46; }
    .notif-type-badge.type-danger { background: #fee2e2; color: #991b1b; }
    .notif-type-badge.type-warning { background: #fef3c7; color: #92400e; }
    .notif-type-badge.type-info { background: #dbeafe; color: #1e40af; }
    .notif-new-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        background: #4361ee;
        color: #fff;
        padding: 2px 7px;
        border-radius: 3px;
        animation: pulse-badge 2s infinite;
    }
    @keyframes pulse-badge {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    .notif-detail-box {
        background: #f8f9fa;
        border-left: 3px solid #dee2e6;
        padding: 10px 14px;
        border-radius: 0 6px 6px 0;
        font-size: 0.82rem;
        line-height: 1.5;
    }
    .notif-detail-box.detail-success { border-left-color: #10b981; }
    .notif-detail-box.detail-danger { border-left-color: #ef4444; }
    .notif-detail-box.detail-warning { border-left-color: #f59e0b; }
    .notif-detail-box.detail-info { border-left-color: #3b82f6; }
    .notif-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 0.78rem;
        color: #6b7280;
    }
    .notif-meta i {
        font-size: 0.72rem;
        margin-right: 3px;
    }
    .notif-card.notif-read .notif-meta {
        color: #b4b9c0;
    }
    .notif-card.notif-read .notif-detail-box {
        background: #f0f1f3;
        border-left-color: #c5c9d1;
    }
    .notif-card.notif-read .notif-sender {
        background: #e5e7eb;
        color: #9ca3af;
    }
    .notif-card.notif-read .notif-type-badge {
        opacity: 0.6;
    }
    .btn-mark-read {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #6b7280;
        transition: all 0.15s ease;
        flex-shrink: 0;
    }
    .btn-mark-read:hover {
        background: #4361ee;
        border-color: #4361ee;
        color: #fff;
        transform: scale(1.05);
    }
    .btn-mark-all {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.82rem;
        font-weight: 500;
        color: #6b7280;
        background: #fff;
        border: 1px solid #e5e7eb;
        padding: 6px 14px;
        border-radius: 8px;
        transition: all 0.15s ease;
    }
    .btn-mark-all:hover {
        background: #f3f4f6;
        color: #374151;
        border-color: #d1d5db;
    }
    .read-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.75rem;
        color: #9ca3af;
        flex-shrink: 0;
        padding: 4px 0;
    }
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    .empty-state-icon {
        width: 72px;
        height: 72px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f6;
        border-radius: 50%;
        margin-bottom: 16px;
        color: #9ca3af;
        font-size: 1.8rem;
    }
    .empty-state-title {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 4px;
    }
    .empty-state-text {
        font-size: 0.85rem;
        color: #9ca3af;
    }
    .notif-sender {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        color: #4b5563;
        background: #f3f4f6;
        padding: 3px 10px;
        border-radius: 20px;
    }
    .notif-sender-avatar {
        width: 20px;
        height: 20px;
        background: #e5e7eb;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        color: #6b7280;
    }
</style>

<div class="row justify-content-center">
<div class="col-lg-8">

    @if($notifications->count() > 0)
    <div class="d-flex justify-content-between align-items-center mb-4">
        <span class="text-muted small">{{ $notifications->count() }} notifikasi</span>
        <form action="{{ route('notifications.markAllRead') }}" method="POST" id="markAllForm">
            @csrf
            <button type="submit" class="btn-mark-all" id="markAllBtn">
                <i class="bi bi-check2-all"></i>
                <span>Tandai Semua Dibaca</span>
            </button>
        </form>
    </div>
    @endif

    @forelse($notifications as $notif)
    <div class="text-decoration-none" style="display:block; cursor:pointer;"
         onclick="handleNotifClick(event, this)"
         data-notif-id="{{ $notif->id }}"
         data-notif-url="{{ $notif->url ?? '' }}"
         data-notif-read="{{ $notif->is_read ? 'true' : 'false' }}">
    <div class="card mb-2 border-0 shadow-sm notif-card {{ !$notif->is_read ? 'notif-unread' : 'notif-read' }}">
        <div class="card-body py-3 px-4">
            <div class="d-flex justify-content-between align-items-start gap-3">
                <div class="flex-grow-1">

                    {{-- Badges --}}
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        @if(!$notif->is_read)
                            <span class="notif-new-badge">
                                <span style="width:5px;height:5px;background:#fff;border-radius:50%;display:inline-block;"></span>
                                Baru
                            </span>
                        @endif
                        <span class="notif-type-badge type-{{ $notif->type }}">
                            <i class="bi bi-{{ $notif->type === 'success' ? 'check-circle' : ($notif->type === 'danger' ? 'exclamation-triangle' : ($notif->type === 'warning' ? 'exclamation-circle' : 'info-circle')) }}"></i>
                            {{ ucfirst($notif->type) }}
                        </span>
                    </div>

                    {{-- Title --}}
                    <h6 class="mb-1 fw-semibold" style="font-size:0.95rem; color:#1f2937;">{{ $notif->title }}</h6>

                    {{-- Message --}}
                    <p class="mb-3" style="font-size:0.85rem; color:#4b5563; line-height:1.6;">{{ $notif->message }}</p>

                    {{-- Sender --}}
                    @if($notif->sender)
                    <div class="mb-3">
                        <span class="notif-sender">
                            <span class="notif-sender-avatar"><i class="bi bi-person-fill"></i></span>
                            <strong>{{ $notif->sender->name }}</strong>
                            <span style="color:#9ca3af;">·</span>
                            <span style="color:#6b7280;">{{ $notif->sender->role }}</span>
                        </span>
                    </div>
                    @endif

                    {{-- Description / Detail --}}
                    @if($notif->description)
                    <div class="notif-detail-box detail-{{ $notif->type }} mb-3">
                        <i class="bi bi-file-text me-1" style="color:#9ca3af;"></i>
                        {{ $notif->description }}
                    </div>
                    @endif

                    {{-- Meta --}}
                    <div class="notif-meta">
                        <span><i class="bi bi-clock"></i>{{ $notif->created_at->diffForHumans() }}</span>
                        <span><i class="bi bi-calendar3"></i>{{ $notif->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>

                {{-- Action / Status --}}
                @if(!$notif->is_read)
                <form action="{{ route('notifications.markRead', $notif) }}" method="POST" class="mark-read-form" style="margin:0;" onclick="event.stopPropagation();">
                    @csrf
                    <button type="submit" class="btn-mark-read mark-read-btn" title="Tandai dibaca">
                        <i class="bi bi-check2"></i>
                    </button>
                </form>
                @else
                <span class="read-status">
                    <i class="bi bi-check-circle-fill"></i>
                </span>
                @endif
            </div>
        </div>
    </div>
    </div>
    @empty
    <div class="card border-0 shadow-sm">
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-bell-slash"></i>
            </div>
            <div class="empty-state-title">Tidak Ada Notifikasi</div>
            <div class="empty-state-text">Semua notifikasi sudah dibaca atau belum ada notifikasi baru.</div>
        </div>
    </div>
    @endforelse

    @if($notifications->hasPages())
    <div class="mt-4">{{ $notifications->links() }}</div>
    @endif

</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark read single
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (this.dataset.submitted) {
                e.preventDefault();
                return;
            }
            this.dataset.submitted = true;
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:14px;height:14px;border-width:2px;" role="status"></span>';

            const form = this.closest('form');
            form.submit();
        });
    });

    // Mark all read
    document.getElementById('markAllBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        if (this.dataset.submitted) {
            return;
        }
        this.dataset.submitted = true;
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:14px;height:14px;border-width:2px;" role="status"></span><span>Memproses...</span>';

        // Submit the form
        this.closest('form').submit();
    });
});

function handleNotifClick(event, element) {
    // Jangan trigger jika klik pada button atau form
    if (event.target.closest('.mark-read-form') || event.target.closest('button')) {
        event.stopPropagation();
        return;
    }

    // Ambil data dari wrapper element
    const notifId = element.getAttribute('data-notif-id');
    const url = element.getAttribute('data-notif-url');
    const isRead = element.getAttribute('data-notif-read') === 'true';

    // Hanya trigger action untuk notifikasi yang belum dibaca
    if (!isRead) {
        // Jika belum dibaca, submit form untuk mark read (yang nanti redirect ke URL)
        const form = element.querySelector('.mark-read-form');
        if (form) {
            form.submit();
        }
    }
    // Jika sudah dibaca, jangan lakukan apa-apa (atau bisa tambahkan action lain jika diperlukan)
}
</script>
@endsection
