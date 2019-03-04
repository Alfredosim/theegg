<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'apellido', 'cedula', 'active', 'telefono', 'telefono_op', 'direccion',
        'direccion_op',
    ];


    // public function scopeNombreFill($query, $name)
    // {
    //     return $query->where('nombre', 'like', $name);
    // }
}
