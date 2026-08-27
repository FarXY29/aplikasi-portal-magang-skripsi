<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Ambil daftar notifikasi terbaru & unread count untuk polling realtime
     */
    public function getUnread(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['unread_count' => 0, 'notifications' => []]);
        }

        $unreadCount = $user->unreadNotifications()->count();
        $recentNotifications = $user->notifications()
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'read' => !is_null($notif->read_at),
                    'title' => $notif->data['title'] ?? 'Notifikasi Baru',
                    'message' => $notif->data['message'] ?? '',
                    'type' => $notif->data['type'] ?? 'info',
                    'action_url' => $notif->data['action_url'] ?? route('dashboard'),
                    'time_ago' => $notif->created_at ? $notif->created_at->diffForHumans() : '',
                    'created_at' => $notif->created_at ? $notif->created_at->toIso8601String() : '',
                ];
            });

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $recentNotifications,
        ]);
    }

    /**
     * Tandai 1 notifikasi telah dibaca
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Tandai semua notifikasi telah dibaca
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }
}
