<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $selectedUserId = $request->query('user_id');
        $selectedUser = $selectedUserId ? User::find($selectedUserId) : null;

        // 1. Mark as read ONLY if we specifically selected a user to chat with
        if ($selectedUserId) {
            Notification::where('sender_id', $selectedUserId)
                ->where('receiver_id', $currentUser->id)
                ->update(['is_read' => true]);
        }

        // 2. Load users with unread counts and latest message for sidebar
        $users = User::where('id', '!=', $currentUser->id)
            ->withCount(['sentNotifications as unread_count' => function($query) use ($currentUser) {
                $query->where('receiver_id', $currentUser->id)
                    ->where('is_read', false);
            }])
            ->with(['sentNotifications' => function($query) {
                $query->latest()->limit(1);
            }])
            ->get();

        // 3. Build message query
        $query = Notification::with('user')->latest();

        if ($selectedUser) {
            $query->where(function($q) use ($selectedUser, $currentUser) {
                $q->where('sender_id', $currentUser->id)->where('receiver_id', $selectedUser->id);
            })->orWhere(function($q) use ($currentUser, $selectedUser) {
                $q->where('sender_id', $selectedUser->id)->where('receiver_id', $currentUser->id);
            });
        } else {
            $query->where(function($q) use ($currentUser) {
                $q->where('receiver_role', 'All')
                ->orWhere('receiver_role', $currentUser->role);
            })->whereNull('receiver_id');
        }

        $notifications = $query->get()->reverse();

        return view('notifications.index', compact('notifications', 'users', 'selectedUser', 'currentUser'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'message' => 'required|string|max:500',
        'type' => 'required|in:CHAT,SYSTEM_NOTE',
        'receiver_id' => 'nullable|exists:users,id'
    ]);

    Notification::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $request->receiver_id,
        'message' => $request->message,
        // Fix: Use an empty string or ensure your migration allows NULL
        'receiver_role' => $request->receiver_id ? 'Admin' : 'All', 
        'type' => $request->type,
        'is_read' => false,
    ]);

    return back()->with('success', 'Message sent!');
}

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNotificationRequest $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
