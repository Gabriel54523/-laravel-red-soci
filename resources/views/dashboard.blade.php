@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Perfil del usuario -->
    <div class="col-span-1 bg-white rounded-2xl shadow-lg p-6 flex flex-col items-center text-center">
        <div class="w-24 h-24 rounded-full bg-indigo-100 flex items-center justify-center mb-4 shadow">
            <i class="fas fa-user text-indigo-500 text-5xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-1">{{ Auth::user()->name }}</h2>
        <p class="text-gray-500 mb-2">{{ Auth::user()->email }}</p>
        <span class="inline-block px-3 py-1 text-xs rounded-full bg-green-100 text-green-700 mb-4">
            <i class="fas fa-circle text-xs mr-1"></i>En línea
        </span>
        <div class="flex space-x-2 mt-2">
            <a href="{{ route('perfil.editar') }}" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-600 px-4 py-2 rounded-lg font-semibold transition flex items-center">
                <i class="fas fa-user-edit mr-2"></i>Editar Perfil
            </a>
            <a href="{{ route('perfil.configuracion') }}" class="bg-gray-50 hover:bg-gray-100 text-gray-600 px-4 py-2 rounded-lg font-semibold transition flex items-center">
                <i class="fas fa-cog mr-2"></i>Configuración
            </a>
        </div>
    </div>

    <!-- Conversaciones recientes -->
    <div class="col-span-2 bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-comments mr-2 text-indigo-500"></i>Conversaciones recientes
            </h3>
                <div class="space-y-4">
            @forelse($recentConversations ?? [] as $conversation)
                <div class="flex items-center justify-between bg-indigo-50 hover:bg-indigo-100 rounded-lg p-4 transition">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-indigo-200 flex items-center justify-center mr-3">
                            <i class="fas fa-user text-indigo-600"></i>
                                </div>
                                <div>
                            <div class="font-semibold text-gray-800">{{ $conversation->contact->name ?? 'Sin nombre' }}</div>
                            <div class="text-gray-500 text-sm">{{ Str::limit($conversation->latestMessage->body ?? 'Sin mensajes', 40) }}</div>
                        </div>
                    </div>
                    <a href="{{ route('conversations.show', $conversation->id) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition">Ver</a>
                </div>
            @empty
                <div class="text-gray-400 text-center py-8">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <div>No tienes conversaciones recientes.</div>
                </div>
            @endforelse
        </div>
    </div>
                    </div>

<!-- Contactos favoritos -->
<div class="mt-10 bg-white rounded-2xl shadow-lg p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-star mr-2 text-yellow-400"></i>Contactos favoritos
    </h3>
    <div class="flex flex-wrap gap-6">
        @forelse($favoriteContacts ?? [] as $contact)
            <div class="flex flex-col items-center bg-indigo-50 rounded-xl p-4 w-40 shadow hover:shadow-md transition">
                <div class="w-12 h-12 rounded-full bg-indigo-200 flex items-center justify-center mb-2">
                    <i class="fas fa-user text-indigo-600"></i>
                    </div>
                <div class="font-semibold text-gray-800">{{ $contact->name }}</div>
                <div class="text-gray-500 text-xs mb-2">{{ $contact->phone_number }}</div>
                <a href="{{ route('contacts.show', $contact->id) }}" class="text-indigo-500 hover:text-indigo-700 text-sm font-semibold">Ver perfil</a>
            </div>
        @empty
            <div class="text-gray-400 text-center py-8 w-full">
                <i class="fas fa-user-friends text-3xl mb-2"></i>
                <div>No tienes contactos favoritos.</div>
        </div>
        @endforelse
    </div>
</div>
@endsection 