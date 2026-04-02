<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationManagementController extends Controller
{
 public function index()
    {
        $notifications = Notification::latest()->paginate(20);

        return view('pages.notifications.index', compact('notifications'));
    }
}
