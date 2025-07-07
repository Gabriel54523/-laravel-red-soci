@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Configuración de Usuario</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('perfil.configuracion.actualizar') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">(Ejemplo) Notificaciones:</label>
            <select name="notificaciones" class="form-control">
                <option value="1">Activadas</option>
                <option value="0">Desactivadas</option>
            </select>
        </div>
        <!-- Puedes agregar más campos de configuración aquí -->
        <button type="submit" class="btn btn-primary">Guardar Configuración</button>
    </form>
</div>
@endsection 