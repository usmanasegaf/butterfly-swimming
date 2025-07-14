<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Tandai notifikasi spesifik sebagai sudah dibaca.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|string', // ID notifikasi adalah UUID
        ]);

        $user = Auth::user();
        if ($user) {
            $notification = $user->notifications()->find($request->notification_id);
            if ($notification) {
                $notification->markAsRead();
            }
        }

        // Redirect ke URL yang ditentukan di notifikasi, atau ke dashboard jika tidak ada
        return redirect($notification->data['action_url'] ?? route('dashboard'));
    }

    /**
     * Tandai semua notifikasi yang belum dibaca sebagai sudah dibaca.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai sudah dibaca.');
    }
}
