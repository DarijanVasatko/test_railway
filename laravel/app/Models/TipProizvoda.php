<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipProizvoda extends Model
{
    protected $table = 'tip_proizvoda';
    protected $primaryKey = 'id_tip';
    public $timestamps = false;

    protected $fillable = ['naziv_tip'];

    public function proizvodi()
    {
        return $this->hasMany(Proizvod::class, 'tip_id', 'id_tip');
    }
}
