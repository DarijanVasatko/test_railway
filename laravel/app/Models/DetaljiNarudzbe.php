<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetaljiNarudzbe extends Model
{
    use HasFactory;

    protected $table = 'detalji_narudzbe';
    protected $primaryKey = 'DetaljiNarudzbe_ID';
    public $timestamps = true; // migration includes timestamps

    protected $fillable = [
        'Narudzba_ID',
        'Proizvod_ID',
        'Kolicina',
    ];

    /** 🔗 Relations */
    public function narudzba()
    {
        return $this->belongsTo(Narudzba::class, 'Narudzba_ID');
    }

    public function proizvod()
    {
        return $this->belongsTo(Proizvod::class, 'Proizvod_ID');
    }

    /**
     
     */
    public function getKolicinaAttribute()
    {
        return $this->attributes['Kolicina'] ?? null;
    }

    /**
     * Return the quantity for this detail.
     */
    public function getCijenaAttribute()
    {
        // If the detail row stores a Cijena column (e.g. future migrations), prefer it
        if (array_key_exists('Cijena', $this->attributes)) {
            return $this->attributes['Cijena'];
        }

        return optional($this->proizvod)->Cijena ?? 0;
    }
}
