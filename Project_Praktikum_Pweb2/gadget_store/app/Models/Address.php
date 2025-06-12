<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone_number',
        'address_line1',
        'address_line2',
        'city',
        'province',
        'postal_code',
        'country',
        'is_primary_shipping',
        'is_primary_billing',
    ];

    protected $casts = [
        'is_primary_shipping' => 'boolean',
        'is_primary_billing' => 'boolean',
    ];

    // Relasi: Address dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
