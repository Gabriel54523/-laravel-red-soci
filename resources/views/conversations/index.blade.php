@extends('layouts.app')

@section('title', 'Conversaciones')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Conversaciones</h1>
        <a href="{{ route('contacts.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
            <i class="fas fa-plus mr-2"></i>
            Nuevo Chat
        </a>
    </div>

    <!-- Conversations List -->
    @if($conversations->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($conversations as $conversation)
                    <li>
                        <a href="{{ route('conversations.show', $conversation) }}" 
                           class="block hover:bg-gray-50">
                            <div class="px-4 py-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3 class="text-sm font-medium text-gray-900">
                                                    {{ $conversation->contact->name }}
                                                </h3>
                                                @if($conversation->latestMessage)
                                                    <p class="text-sm text-gray-500 mt-1">
                                                        {{ Str::limit($conversation->latestMessage->content, 60) }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="flex flex-col items-end">
                                                @if($conversation->last_message_at)
                                                    <p class="text-xs text-gray-400">
                                                        {{ $conversation->last_message_at->diffForHumans() }}
                                                    </p>
                                                @endif
                                                @if($conversation->unread_count > 0)
                                                    <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        {{ $conversation->unread_count }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-comments text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes conversaciones</h3>
            <p class="text-gray-500 mb-6">Comienza una conversación con uno de tus contactos.</p>
            <a href="{{ route('contacts.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <i class="fas fa-plus mr-2"></i>
                Ver Contactos
            </a>
        </div>
    @endif
</div>
@endsection 