<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Jika Anda akan menggunakan Sanctum untuk API

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number', // Tambahan dari migrasi kita
        'role',         // Tambahan dari migrasi kita
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Otomatis hash password saat di-set
    ];

    // Relasi: User memiliki banyak Address
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // Relasi: User memiliki banyak Order
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relasi: User memiliki banyak Review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Helper untuk cek role (opsional tapi berguna)
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
