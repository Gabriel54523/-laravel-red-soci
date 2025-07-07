<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    protected $fillable = [
        'user_id',
        'phone_number',
        'name',
        'profile_photo',
        'status',
        'last_seen_at',
        'is_online',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'is_online' => 'boolean',
    ];

    /**
     * Get the user that owns this contact
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get conversations with this contact
     */
    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'contact_id');
    }

    /**
     * Update contact's online status
     */
    public function updateOnlineStatus($isOnline = true)
    {
        $this->update([
            'is_online' => $isOnline,
            'last_seen_at' => now(),
        ]);
    }
}
