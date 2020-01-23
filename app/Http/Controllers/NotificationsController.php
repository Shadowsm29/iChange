<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        auth()->user()->unreadNotifications->markAsRead();
        // dd(auth()->user()->notifications);

        return view("notifications.index", [
            "notifications" => auth()->user()->notifications()->paginate(10)
        ]);
    }
}
