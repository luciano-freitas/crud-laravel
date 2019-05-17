<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    public function iten()
    {
        return $this->hasMany('App\Iten', 'pedido_id', 'id');
    }
}
