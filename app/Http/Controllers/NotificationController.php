<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->with('sender')
            ->orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('shared.notifications.index', compact('notifications'));
    }

    public function markRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) abort(403);
        $notification->update(['is_read' => true, 'read_at' => now()]);

        // Redirect ke URL terkait jika ada dan valid
        if ($notification->url) {
            return redirect($notification->url);
        }

        // Default redirect ke halaman notifikasi (lebih aman dari back())
        return redirect()->route('notifications.index')->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    public function markAllRead()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return redirect()->route('notifications.index')
            ->with('success', "Semua notifikasi ({$count}) telah ditandai dibaca.");
    }

    public function getUnread()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->with('sender')
            ->latest()
            ->take(10)
            ->get(['id', 'title', 'message', 'description', 'type', 'url', 'sender_id', 'created_at']);

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications,
        ]);
    }
}
