<?php

namespace sialas;

use Illuminate\Database\Eloquent\Model;

class Remesas extends Model
{
    protected $fillable = ['caja_id','banco_id','monto','transaccion'];


}
