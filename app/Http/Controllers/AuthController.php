<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\SmsService;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            // Update online status
            Auth::user()->updateOnlineStatus(true);
            
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->withInput();
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'status' => '¡Hola! Estoy usando la aplicación de mensajería.',
            'phone_verified_at' => now(), // Marcar como verificado automáticamente
        ]);

        Auth::login($user);
        $user->updateOnlineStatus(true);

        return redirect('/dashboard')->with('success', '¡Registro exitoso!');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        // Update offline status
        if (Auth::check()) {
            Auth::user()->updateOnlineStatus(false);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showPhoneVerificationForm()
    {
        return view('auth.verify-phone');
    }

    public function verifyPhoneCode(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);
        $userId = session('pending_user_id');
        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('register')->withErrors(['phone_number' => 'Usuario no encontrado.']);
        }
        if ($user->phone_verification_code === $request->code) {
            $user->phone_verified_at = now();
            $user->phone_verification_code = null;
            $user->save();
            Auth::login($user);
            $user->updateOnlineStatus(true);
            session()->forget('pending_user_id');
            return redirect('/dashboard')->with('success', '¡Teléfono verificado correctamente!');
        } else {
            return back()->withErrors(['code' => 'El código ingresado es incorrecto.']);
        }
    }
}
