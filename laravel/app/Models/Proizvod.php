<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proizvod extends Model
{
    use HasFactory;

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

    public function getSlikaUrlAttribute()
{

    if (!$this->Slika) {
        return asset('img/no-image.png');
    }


    if (str_starts_with($this->Slika, 'http://') || str_starts_with($this->Slika, 'https://')) {
        return $this->Slika;
    }

    return asset('storage/' . $this->Slika);
}

    
}
