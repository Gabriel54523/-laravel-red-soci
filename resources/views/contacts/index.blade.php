@extends('layouts.app')

@section('title', 'Contactos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Contactos</h1>
        <a href="{{ route('contacts.create') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
            <i class="fas fa-plus mr-2"></i>
            Agregar Contacto
        </a>
    </div>

    <!-- Contacts List -->
    @if($contacts->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($contacts as $contact)
                    <li>
                        <div class="px-4 py-4 flex items-center justify-between hover:bg-gray-50">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $contact->name }}</h3>
                                        @if($contact->is_online)
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-circle text-green-400 mr-1"></i>
                                                En línea
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500">{{ $contact->phone_number }}</p>
                                    @if($contact->status)
                                        <p class="text-sm text-gray-400">{{ $contact->status }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('contacts.start-conversation', $contact) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" 
                                   title="Iniciar conversación">
                                    <i class="fas fa-comment"></i>
                                </a>
                                <a href="{{ route('contacts.show', $contact) }}" 
                                   class="text-gray-600 hover:text-gray-900" 
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('contacts.edit', $contact) }}" 
                                   class="text-blue-600 hover:text-blue-900" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900" 
                                            title="Eliminar"
                                            onclick="return confirm('¿Estás seguro de que quieres eliminar este contacto?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-address-book text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes contactos</h3>
            <p class="text-gray-500 mb-6">Comienza agregando tu primer contacto para poder chatear.</p>
            <a href="{{ route('contacts.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <i class="fas fa-plus mr-2"></i>
                Agregar Primer Contacto
            </a>
        </div>
    @endif
</div>
@endsection 