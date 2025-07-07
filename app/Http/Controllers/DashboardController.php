<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        $recentConversations = $user->conversations()
            ->with(['contact', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->limit(5)
            ->get();

        $contactsCount = $user->contacts()->count();
        $conversationsCount = $user->conversations()->count();
        $unreadMessagesCount = $user->conversations()->sum('unread_count');

        return view('dashboard', compact(
            'recentConversations',
            'contactsCount',
            'conversationsCount',
            'unreadMessagesCount'
        ));
    }
} 