<?php

namespace sialas;

use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    protected $fillable = ['nombre', 'contacto', 'nif', 'direccion','correo','telefono'];


    public static function buscar($nombre,$estado){
      return Proveedores::nombre($nombre)->estado($estado)->orderBy('nombre')->paginate(8);
    }

    public function scopeNombre($query, $nombre){
      if (trim($nombre)!="") {
        $query->where('nombre','LIKE','%'.$nombre.'%');
      }
    }

    public function scopeEstado($query, $estado){
      if($estado == null){
        $estado = 1;
      }
      $query->where('estado', $estado);
    }
}
