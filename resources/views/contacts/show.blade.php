@extends('layouts.app')

@section('title', 'Detalle de Contacto')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center space-x-4 mb-6">
            <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-3xl text-gray-600"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $contact->name }}</h2>
                <p class="text-gray-500">{{ $contact->phone_number }}</p>
                @if($contact->is_online)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-circle text-green-400 mr-1"></i>En línea
                    </span>
                @endif
            </div>
        </div>
        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-700">Estado</h3>
            <p class="text-gray-900">{{ $contact->status ?? 'Sin estado' }}</p>
        </div>
        <div class="mb-4">
            <h3 class="text-sm font-medium text-gray-700">Última vez en línea</h3>
            <p class="text-gray-900">
                @if($contact->last_seen_at)
                    {{ $contact->last_seen_at->diffForHumans() }}
                @else
                    Nunca
                @endif
            </p>
        </div>
        <div class="flex space-x-2 mt-6">
            <a href="{{ route('contacts.edit', $contact) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('contacts.start-conversation', $contact) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <i class="fas fa-comment mr-2"></i>Iniciar Conversación
            </a>
            <a href="{{ route('contacts.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Volver
            </a>
        </div>
    </div>
</div>
@endsection 