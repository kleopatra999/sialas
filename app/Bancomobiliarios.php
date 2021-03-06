<?php

namespace sialas;

use Illuminate\Database\Eloquent\Model;

class Bancomobiliarios extends Model
{
    //
    protected $fillable = ['banco_id','mobiliario_id','cantidad','detalle','cheque'];


  public static function nombreBancos($id){
      $n= Bancos::find($id);
      return $n->nombre; 
    }
     public static function nombreMobiliarios($id){
      $n= Mobiliarios::find($id);
      return $n->nombre; 
    }
}