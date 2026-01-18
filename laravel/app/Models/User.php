<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     *
     * @var list<string>
     */
  protected $fillable = [
    'ime',
    'prezime',
    'email',
    'password',
    'telefon',
];


    /**
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getFullNameAttribute()
{
    return "{$this->ime} {$this->prezime}";
}

public function addresses()
{
    return $this->hasMany(UserAddress::class, 'user_id', 'id');
}

public function narudzbe()
{
    return $this->hasMany(Narudzba::class, 'Kupac_ID', 'id');
}

public function getImePrezimeAttribute(): string
{
    return trim(($this->ime ?? '') . ' ' . ($this->prezime ?? ''));
}



}
