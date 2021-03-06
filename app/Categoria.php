<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'descripcion'
    ];
    
    /**
     * Obtiene las transacciones de la categoria.
     */
    public function transacciones()
    {
        return $this->hasMany('App\Transaccion');
    }


}
