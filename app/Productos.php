<?php

namespace sialas;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
  protected $fillable = ['nombre','marca_id','categoria_id','nombre_img'];

  public static function buscar($nombre,$estado){
    return Productos::nombre($nombre)->estado($estado)->orderBy('nombre')->paginate(8);
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
  public static function nombreMarcas($id){
    $n= Marcas::find($id);
    return $n->nombre;
  }
  public static function nombreCategorias($id){
    $n= Categorias::find($id);
    return $n->nombre;
  }
  public static function unidades($presentacion,$cantidad){
    $pres = Presentaciones::find($presentacion);
    return $pres->equivale * $cantidad;
  }
}
