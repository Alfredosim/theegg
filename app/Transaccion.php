<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'asunto', 'categoria_id', 'monto', 'tipo', 'fecha'
    ];
    
    /**
     * Obtiene la categoria de la transaccion.
     */
    public function categoria()
    {
        return $this->belongsTo('App\Categoria', 'categoria_id', 'id')->select('id', 'nombre');
    }

    /**
     * Obtiene el usuario de la transaccion.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
