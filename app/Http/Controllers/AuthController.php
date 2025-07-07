<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Mostrar formulario de registro
    public function showRegister()
    {
        return view('auth.register');
    }

    // Registrar usuario con número de celular
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:users,phone_number',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        // Aquí deberías enviar el SMS con el código de verificación (Twilio)
        // $this->sendVerificationCode($user);

        Auth::login($user);
        return redirect()->route('verify.sms');
    }

    // Mostrar formulario de login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Login con número de celular
    public function login(Request $request)
    {
        $credentials = $request->only('phone_number', 'password');
        if (Auth::attempt(['phone_number' => $credentials['phone_number'], 'password' => $credentials['password']])) {
            // Aquí podrías verificar si el usuario ya está verificado
            return redirect()->intended('/');
        }
        return back()->withErrors(['phone_number' => 'Credenciales incorrectas'])->withInput();
    }

    // Mostrar formulario de verificación SMS
    public function showVerifySms()
    {
        return view('auth.verify-sms');
    }

    // Verificar código SMS
    public function verifySms(Request $request)
    {
        // Aquí deberías comparar el código recibido con el enviado por SMS
        // Por simplicidad, asumimos que el código es correcto
        $user = Auth::user();
        $user->phone_verified_at = now();
        $user->save();
        return redirect('/');
    }

    // Método simulado para enviar SMS (debería implementarse con Twilio)
    protected function sendVerificationCode(User $user)
    {
        // Lógica para enviar SMS usando Twilio
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/login');
    }
} 