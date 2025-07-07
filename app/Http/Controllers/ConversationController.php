<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of conversations
     */
    public function index()
    {
        $conversations = Auth::user()->conversations()
            ->with(['contact', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('conversations.index', compact('conversations'));
    }

    /**
     * Show the specified conversation
     */
    public function show(Conversation $conversation)
    {
        // Ensure user owns this conversation
        if ($conversation->user_id !== Auth::id()) {
            abort(403);
        }

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $conversation->resetUnreadCount();

        $messages = $conversation->messages()
            ->with(['sender', 'attachments'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('conversations.show', compact('conversation', 'messages'));
    }

    /**
     * Start a new conversation with a contact
     */
    public function startWithContact(Contact $contact)
    {
        // Ensure user owns this contact
        if ($contact->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if conversation already exists
        $conversation = Auth::user()->conversations()
            ->where('contact_id', $contact->id)
            ->first();

        if (!$conversation) {
            $conversation = Auth::user()->conversations()->create([
                'contact_id' => $contact->id,
                'last_message_at' => now(),
                'unread_count' => 0,
            ]);
        }

        return redirect()->route('conversations.show', $conversation);
    }

    /**
     * Send a message in a conversation
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        // Ensure user owns this conversation
        if ($conversation->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
            'attachments.*' => 'nullable|file|max:10240', // 10MB max
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => Auth::id(),
            'content' => $request->content,
            'message_type' => 'text',
            'is_read' => false,
        ]);

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message-attachments', 'public');
                
                $message->attachments()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        // Update conversation
        $conversation->update([
            'last_message_at' => now(),
        ]);

        // Increment unread count for the contact (if they were the last sender)
        $lastMessage = $conversation->messages()->latest()->first();
        if ($lastMessage && $lastMessage->sender_id !== Auth::id()) {
            $conversation->incrementUnreadCount();
        }

        return back()->with('success', 'Mensaje enviado.');
    }

    /**
     * Delete a conversation
     */
    public function destroy(Conversation $conversation)
    {
        // Ensure user owns this conversation
        if ($conversation->user_id !== Auth::id()) {
            abort(403);
        }

        $conversation->delete();

        return redirect()->route('conversations.index')->with('success', 'Conversación eliminada.');
    }
} 