<?php

namespace App\Http\Resources\Transaccion;

use Illuminate\Http\Resources\Json\JsonResource;

class TransaccionesResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
