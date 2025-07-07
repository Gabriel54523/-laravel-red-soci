<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario principal
        $user = User::create([
            'name' => 'Usuario Demo',
            'email' => 'demo@example.com',
            'phone_number' => '+1234567890',
            'password' => Hash::make('password'),
            'status' => '¡Hola! Estoy usando la aplicación de mensajería.',
        ]);

        // Datos de contactos
        $contactsData = [
            [
                'name' => 'Juan Pérez',
                'phone_number' => '+1234567891',
                'status' => 'Disponible para chatear',
                'email' => 'juan@example.com',
            ],
            [
                'name' => 'María García',
                'phone_number' => '+1234567892',
                'status' => 'En una reunión',
                'email' => 'maria@example.com',
            ],
            [
                'name' => 'Carlos López',
                'phone_number' => '+1234567893',
                'status' => '¡Hola! ¿Cómo estás?',
                'email' => 'carlos@example.com',
            ],
        ];

        foreach ($contactsData as $contactData) {
            // Crear usuario para el contacto
            $contactUser = User::create([
                'name' => $contactData['name'],
                'email' => $contactData['email'],
                'phone_number' => $contactData['phone_number'],
                'password' => Hash::make('password'),
                'status' => $contactData['status'],
            ]);

            // Crear contacto asociado al usuario principal
            $contact = $user->contacts()->create([
                'name' => $contactData['name'],
                'phone_number' => $contactData['phone_number'],
                'status' => $contactData['status'],
            ]);

            // Crear conversación
            $conversation = $user->conversations()->create([
                'contact_id' => $contact->id,
                'last_message_at' => now(),
                'unread_count' => rand(0, 3),
            ]);

            // Mensajes de ejemplo alternando entre usuario principal y contacto
            $messages = [
                '¡Hola! ¿Cómo estás?',
                'Todo bien, gracias. ¿Y tú?',
                'Perfecto, ¿qué planes tienes para hoy?',
                'Nada especial, solo trabajando.',
                '¡Que tengas un buen día!',
            ];

            foreach ($messages as $index => $content) {
                $message = $conversation->messages()->create([
                    'sender_id' => $index % 2 == 0 ? $user->id : $contactUser->id,
                    'content' => $content,
                    'message_type' => 'text',
                    'is_read' => $index < 3,
                    'read_at' => $index < 3 ? now() : null,
                ]);
            }
        }
    }
}
