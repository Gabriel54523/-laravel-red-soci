@extends('layouts.app')

@section('title', 'Chat con ' . $conversation->contact->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Chat Header -->
    <div class="bg-white shadow rounded-t-lg border-b">
        <div class="px-6 py-4 flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('conversations.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-user text-gray-600"></i>
                </div>
                <div>
                    <h2 class="text-lg font-medium text-gray-900">{{ $conversation->contact->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $conversation->contact->phone_number }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                @if($conversation->contact->is_online)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-circle text-green-400 mr-1"></i>
                        En línea
                    </span>
                @endif
                <form action="{{ route('conversations.destroy', $conversation) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="text-red-600 hover:text-red-900" 
                            onclick="return confirm('¿Estás seguro de que quieres eliminar esta conversación?')">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="bg-white shadow" style="height: 60vh;">
        <div class="h-full flex flex-col">
            <!-- Messages List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="messages-container">
                @foreach($messages as $message)
                    <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md">
                            <div class="flex {{ $message->sender_id === Auth::id() ? 'flex-row-reverse' : 'flex-row' }} items-end space-x-2">
                                @if($message->sender_id !== Auth::id())
                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-user text-gray-600 text-sm"></i>
                                    </div>
                                @endif
                                
                                <div class="bg-{{ $message->sender_id === Auth::id() ? 'blue' : 'gray' }}-100 rounded-lg px-4 py-2">
                                    <p class="text-sm text-gray-900">{{ $message->content }}</p>
                                    
                                    @if($message->hasAttachments())
                                        <div class="mt-2 space-y-2">
                                            @foreach($message->attachments as $attachment)
                                                <div class="border rounded p-2">
                                                    @if($attachment->isImage())
                                                        <img src="{{ $attachment->url }}" alt="{{ $attachment->file_name }}" class="max-w-full h-auto rounded">
                                                    @elseif($attachment->isVideo())
                                                        <video controls class="max-w-full h-auto rounded">
                                                            <source src="{{ $attachment->url }}" type="{{ $attachment->mime_type }}">
                                                        </video>
                                                    @elseif($attachment->isAudio())
                                                        <audio controls class="w-full">
                                                            <source src="{{ $attachment->url }}" type="{{ $attachment->mime_type }}">
                                                        </audio>
                                                    @else
                                                        <a href="{{ $attachment->url }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800">
                                                            <i class="fas fa-file mr-2"></i>
                                                            {{ $attachment->file_name }}
                                                            <span class="text-xs text-gray-500 ml-2">({{ $attachment->formatted_size }})</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $message->created_at->format('H:i') }}
                                        @if($message->is_read && $message->sender_id === Auth::id())
                                            <i class="fas fa-check-double text-blue-500 ml-1"></i>
                                        @elseif($message->sender_id === Auth::id())
                                            <i class="fas fa-check text-gray-400 ml-1"></i>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message Input -->
            <div class="border-t p-4">
                <form action="{{ route('conversations.send-message', $conversation) }}" method="POST" enctype="multipart/form-data" class="flex space-x-2">
                    @csrf
                    <div class="flex-1">
                        <input type="text" name="content" 
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Escribe un mensaje..." required>
                    </div>
                    <div class="flex items-center space-x-2">
                        <label for="attachments" class="cursor-pointer text-gray-600 hover:text-gray-900">
                            <i class="fas fa-paperclip"></i>
                        </label>
                        <input type="file" id="attachments" name="attachments[]" multiple class="hidden">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-scroll to bottom of messages
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // File input preview
    document.getElementById('attachments').addEventListener('change', function(e) {
        const files = e.target.files;
        if (files.length > 0) {
            console.log('Archivos seleccionados:', files.length);
        }
    });
</script>
@endpush
@endsection 