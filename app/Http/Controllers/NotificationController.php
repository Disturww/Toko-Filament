<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function unread(): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['notifications' => []]);
        }

        $notifications = DatabaseNotification::where('notifiable_id', Auth::id())
            ->whereNull('read_at')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($n) => [
                'id' => $n->id,
                'title' => $n->data['title'] ?? 'Notifikasi',
                'body' => $n->data['body'] ?? '',
                'status' => $n->data['status'] ?? 'primary',
                'iconColor' => $n->data['iconColor'] ?? 'primary',
                'created_at' => $n->created_at->diffForHumans(),
            ]);

        return response()->json(['notifications' => $notifications]);
    }

    public function markRead(string $id): JsonResponse
    {
        if (! Auth::check()) {
            return response()->json(['ok' => false]);
        }

        DatabaseNotification::where('notifiable_id', Auth::id())
            ->where('id', $id)
            ->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}
