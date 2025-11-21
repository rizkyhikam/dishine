<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail 
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama', // sesuaikan dengan kolom database Anda (nama/name)
        'email',
        'password',
        'role',
        'alamat',
        'no_hp',
        'remember_token',
        'province_id', 
        'city_id',     
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi: User hasMany Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}