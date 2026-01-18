<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kupac extends Model
{
    use HasFactory;

    // 👉 FORCE correct table name
    protected $table = 'kupac';
    protected $primaryKey = 'Kupac_ID';
    public $timestamps = false; // ili true ako u tablici imaš created_at / updated_at

    protected $fillable = [
        'Ime',
        'Prezime',
        'Email',
        // dodaj ostala polja koja imaš u kupac tablici
    ];

    /** Relacije */
    public function narudzbe()
    {
        return $this->hasMany(Narudzba::class, 'Kupac_ID', 'Kupac_ID');
    }

    // Quality of life accessor: $kupac->ImePrezime
    public function getImePrezimeAttribute(): string
    {
        return trim(($this->Ime ?? '') . ' ' . ($this->Prezime ?? ''));
    }
}
