<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Aktifkan jika Anda menggunakan verifikasi email Breeze
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // <--- TAMBAHKAN IMPORT INI

class User extends Authenticatable // implements MustVerifyEmail (Aktifkan jika perlu)
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Pastikan 'role' sudah ada di sini
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array // Atau getCasts() untuk Laravel < 11
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Method untuk mengecek peran (sudah ada dari sebelumnya)
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKurir(): bool
    {
        return $this->role === 'kurir';
    }

    public function isUser(): bool // Atau peran default lainnya
    {
        return $this->role === 'user';
    }

    /**
     * Mendefinisikan relasi bahwa satu User bisa memiliki banyak Order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany // <--- TAMBAHKAN METHOD RELASI INI
    {
        return $this->hasMany(Order::class);
        // Argumen kedua opsional adalah foreign key di tabel 'orders' jika berbeda dari 'user_id'
        // Argumen ketiga opsional adalah local key di tabel 'users' jika berbeda dari 'id'
    }
}
