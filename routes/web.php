<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Verificación de teléfono (fuera del middleware de auth)
Route::get('/verificar-telefono', [\App\Http\Controllers\AuthController::class, 'showPhoneVerificationForm'])->name('verificar.telefono.form');
Route::post('/verificar-telefono', [\App\Http\Controllers\AuthController::class, 'verifyPhoneCode'])->name('verificar.telefono');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Contacts
    Route::resource('contacts', ContactController::class);
    
    // Conversations
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations/{conversation}/messages', [ConversationController::class, 'sendMessage'])->name('conversations.send-message');
    Route::delete('/conversations/{conversation}', [ConversationController::class, 'destroy'])->name('conversations.destroy');
    
    // Start conversation with contact
    Route::get('/contacts/{contact}/start-conversation', [ConversationController::class, 'startWithContact'])->name('contacts.start-conversation');

    // Perfil de usuario
    Route::get('/perfil/editar', [\App\Http\Controllers\UserController::class, 'edit'])->name('perfil.editar');
    Route::post('/perfil/editar', [\App\Http\Controllers\UserController::class, 'update'])->name('perfil.actualizar');

    // Configuración de usuario
    Route::get('/perfil/configuracion', [\App\Http\Controllers\UserController::class, 'configuracion'])->name('perfil.configuracion');
    Route::post('/perfil/configuracion', [\App\Http\Controllers\UserController::class, 'actualizarConfiguracion'])->name('perfil.configuracion.actualizar');
});
