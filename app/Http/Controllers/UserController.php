<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('perfil.editar', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $path = $file->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }
        $user->save();
        return redirect()->route('perfil.editar')->with('success', 'Perfil actualizado correctamente');
    }

    public function configuracion()
    {
        $user = Auth::user();
        return view('perfil.configuracion', compact('user'));
    }

    public function actualizarConfiguracion(Request $request)
    {
        $user = Auth::user();
        // Aquí puedes agregar validaciones y lógica para actualizar configuraciones
        // Por ejemplo, preferencias de notificación, idioma, etc.
        // $user->notificaciones = $request->notificaciones;
        // $user->save();
        return redirect()->route('perfil.configuracion')->with('success', 'Configuración actualizada correctamente');
    }
} 