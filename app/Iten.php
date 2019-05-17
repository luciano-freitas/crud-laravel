<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Iten extends Model
{
    public function produto()
    {
        return $this->hasOne('App\produto',  'id','produto_id');
    }

}
