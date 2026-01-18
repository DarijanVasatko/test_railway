<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proizvod extends Model
{
    use HasFactory; // ✅ This line enables Proizvod::factory()

    protected $table = 'proizvod';
    protected $primaryKey = 'Proizvod_ID';
    public $timestamps = false;

    protected $fillable = [
        'sifra', 'Naziv', 'Opis', 'Cijena', 'KratkiOpis', 'kategorija',
        'StanjeNaSkladistu', 'Slika', 'tip_id'
    ];

    public function kategorija()
    {
        return $this->belongsTo(Kategorija::class, 'kategorija', 'id_kategorija');
    }

    public function tip()
    {
        return $this->belongsTo(TipProizvoda::class, 'tip_id', 'id_tip');
    }

    public function kosarica()
    {
        return $this->hasMany(Kosarica::class, 'proizvod_id', 'Proizvod_ID');
    }

    public function detaljiNarudzbe()
    {
        return $this->hasMany(DetaljiNarudzbe::class, 'Proizvod_ID', 'Proizvod_ID');
    }

    
}
