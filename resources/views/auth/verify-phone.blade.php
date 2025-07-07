@extends('layouts.app')

@section('title', 'Verificar Teléfono')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Verifica tu número de teléfono
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ingresa el código de 6 dígitos que te enviamos por SMS.
            </p>
        </div>
        <form class="mt-8 space-y-6" method="POST" action="{{ route('verificar.telefono') }}">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="code" class="sr-only">Código</label>
                    <input id="code" name="code" type="text" maxlength="6" required autofocus
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm @error('code') border-red-500 @enderror"
                        placeholder="Código de verificación">
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Verificar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 