<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipProizvoda extends Model
{
    protected $table = 'tip_proizvoda';
    protected $primaryKey = 'id_tip';
    public $timestamps = false;

    protected $fillable = [
        'naziv_tip',
        'kategorija_id'
    ];

    public function proizvodi()
    {
        return $this->hasMany(Proizvod::class, 'tip_id', 'id_tip');
    }

    public function kategorija()
    {
        return $this->belongsTo(Kategorija::class, 'kategorija_id', 'id_kategorija');
    }
}
