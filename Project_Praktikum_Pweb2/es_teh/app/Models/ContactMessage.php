<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'subject',
        'message',
        'status',
    ];

    /**
     * Relasi ke User (jika pesan dikirim oleh user yang login).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
