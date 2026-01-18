<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NacinPlacanja extends Model
{
    use HasFactory;

    protected $table = 'nacin_placanja';
    // migration uses NacinPlacanja_ID as primary key
    protected $primaryKey = 'NacinPlacanja_ID';
    public $timestamps = true;

    protected $fillable = ['Opis'];

    /** 🔗 Relations */
    public function narudzbe()
    {
        return $this->hasMany(Narudzba::class, 'NacinPlacanja_ID');
    }

    /**
     * Compatibility accessor: allow using ->naziv in views/controllers
     * while the actual DB column is `Opis`.
     */
    public function getNazivAttribute()
    {
        return $this->attributes['Opis'] ?? null;
    }
}
