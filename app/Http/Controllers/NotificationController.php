<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    public function markAsRead (Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuario no autenticado'], 401);
        }

        $notification = $user->notifications->find($request->id);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true, 'message' => 'Notificación marcada como leída']);
        }

        return response()->json(['success' => false, 'message' => 'Notificación no encontrada'], 404);
    }

    public function list ()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuario no autenticado'], 401);
        }
        $now = Carbon::now();

        $notifications = DatabaseNotification::where('notifiable_id', $user->id)
        ->whereDate('created_at', $now )
        ->get();

        return DataTables::of($notifications)
            ->addColumn('created_at', function ($notification) {
                return $notification->created_at->format('Y-m-d H:i:s');
            })
            ->make(true);
    }

    public function index(){

        return view('notification.index');
    }

    public function unreadList ()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuario no autenticado'], 401);
        }

        $notifications = $user->unreadNotifications;

        return response()->json( $notifications);

    }
}
