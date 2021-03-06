<?php

namespace sialas\Http\Controllers;

use Illuminate\Http\Request;
use sialas\Bitacoras;
use sialas\Http\Requests;
use sialas\Http\Controllers\Controller;
use sialas\Http\Requests\MobiliariosRequest;
use sialas\Mobiliarios;
use sialas\Reparaciones;
use sialas\Tipos;
use sialas\Pagos;
use sialas\Cajas;
use sialas\Bancos;
use sialas\Proveedores;
use Redirect;
use Session;
use View;
use Carbon\Carbon;

class MobiliariosController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(Request $request)
  {
    //
    $state = $request->get('estado');
    $name = $request->get('nombre');
    $mobiliarios= Mobiliarios::buscar($name,$state);//la variable estadoo 0=Vendido, 1=en uso 2=desechado,3=reparacion 4=donado,
    return view('mobiliarios.index',compact('mobiliarios','state','name'));

  }

  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create()
  {
    //
    $c= Tipos::orderBy('nombre','asc')->get();
    $m= Proveedores::where('estado','=', 1)->orderBy('nombre','asc')->get();
    return view('Mobiliarios.create',compact('c','m'));
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(MobiliariosRequest $request)
  {
    Bitacoras::bitacora("Registro de nuevo mobiliario: ".$request['nombre']);
    $mobiliario = new Mobiliarios;
    $mobiliario->codigo = $request->codigo;
    $mobiliario->nombre= $request->nombre;
    $mobiliario->fecha_compra= $request->fecha_compra;
    $mobiliario->precio= $request->precio;
    $mobiliario->descripcion =$request->descripcion;
    $mobiliario->estado = $request->estado;
    $mobiliario->nuevo = $request->nuevo;
    if($request->nuevo == 1){
      $mobiliario->anios=null;
    }else
    {
      if($request->anios == '' && $request->anios2 != ''){
        $mobiliario->anios = $request->anios2;
      }else{
        $mobiliario->anios = $request->anios;
      }
    }
    $mobiliario->proveedor_id = $request->proveedor_id;
    $mobiliario->tipo_id = $request->tipo_id;
    $mobiliario->credito = $request->credito;
    $mobiliario->iva=$request->iva;
    if($request->credito == 0 )
    {
      $mobiliario->interes=null;
      $mobiliario->num_cuotas=null;
      $mobiliario->val_cuotas=null;
      $mobiliario->tiempo_pago=null;
      $mobiliario->cuenta=null;
    }else
    {
      $mobiliario->interes= $request->interes;
      $mobiliario->num_cuotas= $request->num_cuotas;
      $mobiliario->val_cuotas= $request->val_cuotas;
      $mobiliario->tiempo_pago= $request->tiempo_pago;
      $mobiliario->cuenta= $request->cuenta;
    }

    $mobiliario->save();
    return redirect('/mobiliarios')->with('mensaje','Registro Guardado');
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    $mob = Mobiliarios::find($id);
    //Pagos
    $montoTotal = Pagos::where('mobiliario_id',$id)->sum('monto');
    $interTotal = Pagos::where('mobiliario_id',$id)->sum('interes');
    $moraTotal = Pagos::where('mobiliario_id',$id)->sum('mora');
    $cuotas = Pagos::where('mobiliario_id',$id)->count();
    $cuotasc = Pagos::where('mobiliario_id',$id)->where('caja_id', '>',0)->count();
    $cuotasb = Pagos::where('mobiliario_id',$id)->where('banco_id', '>',0)->count();
    $pagoss = Pagos::where('mobiliario_id',$id)->orderBy('created_at','asc')->get();
    $listac = Pagos::where('mobiliario_id',$id)->where('caja_id', '>',0)->orderBy('created_at','asc')->paginate(8);
    $listab = Pagos::where('mobiliario_id',$id)->where('banco_id', '>',0)->orderBy('created_at','asc')->paginate(8);
    $cc = Pagos::where('mobiliario_id',$id)->where('caja_id', '>',0)->sum('monto');
    $cb = Pagos::where('mobiliario_id',$id)->where('banco_id', '>',0)->sum('monto');
    $ic = Pagos::where('mobiliario_id',$id)->where('caja_id', '>',0)->sum('interes');
    $ib = Pagos::where('mobiliario_id',$id)->where('banco_id', '>',0)->sum('interes');
    $mc = Pagos::where('mobiliario_id',$id)->where('caja_id', '>',0)->sum('mora');
    $mb = Pagos::where('mobiliario_id',$id)->where('banco_id', '>',0)->sum('mora');
    //Reparaciones
    $totalreparacion = Reparaciones::where('mobiliario_id',$id)->count();
    $reparar = Reparaciones::where('mobiliario_id',$id)->orderBy('fecha_deposito','asc')->paginate(8);
    $totreparc = Reparaciones::where('mobiliario_id',$id)->where('credito',true)->count();
    $valreparc = Reparaciones::where('mobiliario_id',$id)->where('credito',true)->sum('precio');
    $totreparn = Reparaciones::where('mobiliario_id',$id)->where('credito',false)->count();
    $valreparn = Reparaciones::where('mobiliario_id',$id)->where('credito',false)->sum('precio');
    $ivatotal = Reparaciones::where('mobiliario_id',$id)->sum('iva');

    $totpagorep = Reparaciones::join('pagoreparaciones','reparaciones.id','=','pagoreparaciones.reparacion_id')->where('mobiliario_id', $id)->count();
    $prereptot = Reparaciones::join('pagoreparaciones','reparaciones.id','=','pagoreparaciones.reparacion_id')->where('mobiliario_id', $id)->sum('monto');
    $pagosp = Reparaciones::join('pagoreparaciones','reparaciones.id','=','pagoreparaciones.reparacion_id')->where('mobiliario_id', $id)->get();
    $totalc = $cc + $ic + $mc;
    $totalb = $cb + $ib + $mb;
    $valtotal = $valreparn + $valreparc;
    $hoy = Carbon::now();
    return view('Mobiliarios.show',
    compact(
    'mob',
    'montoTotal',
    'interTotal',
    'cuotas',
    'moraTotal',
    'pagoss',
    'cuotasc',
    'cuotasb',
    'listac',
    'listab',
    'totalc',
    'totalb',
    'cc',
    'cb',
    'ic',
    'ib',
    'mc',
    'mb',
    'totalreparacion',
    'reparar',
    'totreparn',
    'totreparc',
    'valreparc',
    'valreparn',
    'valtotal',
    'totpagorep',
    'prereptot',
    'ivatotal',
    'pagosp',
    'hoy'
    ));
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function edit($id)
  {
    //
    $mobiliarios = Mobiliarios::find($id);
    $c= Tipos::orderBy('nombre','asc')->get();
    $m= Proveedores::where('estado','=', 1)->orderBy('nombre','asc')->get();
    return view('Mobiliarios.edit',compact('mobiliarios','c','m'));
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {
    Bitacoras::bitacora("Modificación de mobiliario: ".$request['nombre']);
    $mobiliarios = Mobiliarios::find($id);
    $mobiliarios->codigo = $request->codigo;
    $mobiliarios->nombre= $request->nombre;
    $mobiliarios->descripcion =$request->descripcion;
    $mobiliarios->estado = $request->estado;
    $mobiliarios->save();
    $mobiliario=$id;
    $p= Proveedores::All();
    if($mobiliarios->estado == 3){
      Session::flash('mensaje','¡Registro Actualizado!');
      return view('reparaciones.crear',compact('id','p','mobiliario'));
    }
    if($mobiliarios->estado == 1){
      $reparacion = Reparaciones::where('mobiliario_id','=', $id)->orderBy('fecha_deposito','desc')->first();
      $date = Carbon::now();
      $date = $date->format('d-m-Y');
      $reparacion->fecha_entrega = $date;
      $reparacion->save();
      Session::flash('mensaje','¡Registro Actualizado!');
      return Redirect::to('/mobiliarios/'.$id);
    }else{
      Session::flash('mensaje','¡Registro Actualizado!');
      return Redirect::to('/mobiliarios');
    }
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    $mobiliarios = Mobiliarios::find($id);
    $mobiliarios->estado=false;
    $mobiliarios->save();
    Bitacoras::bitacora("Mobiliario enviado a papelera: ".$mobiliarios['nombre']);
    Session::flash('mensaje','Registro dado de Baja');
    return Redirect::to('/mobiliarios');
  }

}
