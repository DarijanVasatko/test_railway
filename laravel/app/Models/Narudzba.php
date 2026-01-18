<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Narudzba extends Model
{
    use HasFactory;

    protected $table = 'narudzba';
    protected $primaryKey = 'Narudzba_ID';
    public $timestamps = true;

    protected $fillable = [
        'Kupac_ID',
        'NacinPlacanja_ID',
        'Datum_narudzbe',
        'Ukupni_iznos',
        'Status',   // ⬅️ IMPORTANT: allow updating status
    ];

    /** ---------------------------
     *  🔗 Relations
     *  --------------------------- */

    public function kupac()
    {
        return $this->belongsTo(Kupac::class, 'Kupac_ID');
    }

    public function detalji()
    {
        return $this->hasMany(DetaljiNarudzbe::class, 'Narudzba_ID');
    }

    public function nacinPlacanja()
    {
        return $this->belongsTo(NacinPlacanja::class, 'NacinPlacanja_ID');
    }

    /** ---------------------------
     *  🧩 Attribute Accessors
     *  --------------------------- */

    // Standardized ID alias: $order->id works
    public function getIdAttribute()
    {
        return $this->attributes['Narudzba_ID'] ?? null;
    }

    // For compatibility with "user_id"
    public function getUserIdAttribute()
    {
        return $this->attributes['Kupac_ID'] ?? null;
    }

    // Total price compatibility
    public function getUkupnaCijenaAttribute()
    {
        return $this->attributes['Ukupni_iznos'] ?? null;
    }

    // STATUS (value + default)
    public function getStatusAttribute()
    {
        return $this->attributes['Status'] ?? 'U obradi';
    }

    public function setStatusAttribute($value)
    {
        // protect from invalid values if needed
        $this->attributes['Status'] = $value ?: 'U obradi';
    }
}
