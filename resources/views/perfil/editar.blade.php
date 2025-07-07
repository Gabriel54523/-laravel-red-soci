@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-8 mt-8">
    <h2 class="text-2xl font-bold mb-6 text-center text-indigo-700">Editar Perfil</h2>
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
            {{ session('success') }}
        </div>
    @endif
    <form method="POST" action="{{ route('perfil.actualizar') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="flex flex-col items-center mb-4">
            @if($user->profile_photo)
                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto de perfil" class="w-32 h-32 rounded-full object-cover border-4 border-indigo-200 shadow mb-2">
            @else
                <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center text-5xl text-gray-400 mb-2">
                    <i class="fas fa-user"></i>
                </div>
            @endif
            <label for="profile_photo" class="cursor-pointer text-indigo-600 hover:underline mt-2">Cambiar foto de perfil</label>
            <input type="file" id="profile_photo" name="profile_photo" class="hidden">
            @error('profile_photo')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="name" class="block text-gray-700 font-semibold mb-1">Nombre:</label>
            <input type="text" id="name" name="name" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" value="{{ old('name', $user->name) }}">
            @error('name')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="email" class="block text-gray-700 font-semibold mb-1">Correo electrónico:</label>
            <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" value="{{ old('email', $user->email) }}">
            @error('email')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex justify-center">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-8 rounded-lg shadow transition duration-150">Guardar Cambios</button>
        </div>
    </form>
</div>
@endsection 