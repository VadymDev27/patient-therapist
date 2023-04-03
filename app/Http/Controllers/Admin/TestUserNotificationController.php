<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Pair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class TestUserNotificationController extends Controller
{
    public function index(User $user)
    {
        return view('admin.test-mail.index',['notifications' => $user->notifications, 'user' => $user->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, DatabaseNotification $notification)
    {
        $notification->markAsRead();
        $markdown = app()->make(Markdown::class);
        $html = $markdown->render('notifications::email',$notification->data);

        return view('admin.test-mail.show', ['message' => $html, 'user' => $user->id, 'notification' => $notification->id ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, DatabaseNotification $notification)
    {
        $notification->markAsUnread();
        return redirect()->route('users.notifications.index', ['user' => $user->id]);
    }

}
