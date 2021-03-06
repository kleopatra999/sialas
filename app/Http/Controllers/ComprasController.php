<?php

namespace sialas\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use sialas\Http\Requests;
use sialas\Http\Controllers\Controller;
use sialas\Http\Requests\ComprasRequest;
use sialas\Productos;
use sialas\Proveedores;
use sialas\Presentaciones;
use sialas\Compras;
use sialas\Ubicaciones;
use sialas\Detallecompras;
use sialas\Users;

class ComprasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
       $state = $request->get('estado');
       $cajasActivas= Compras::buscar($state);
       return view('compras.index',compact('cajasActivas','cajasInactivas','state'));
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $proveedores=Proveedores::where('estado',true)->orderBy('nombre')->get();
      $productos=Productos::where('estado',true)->orderBy('nombre')->get();
      //return $productos;
      return view('Compras.create',compact('productos','proveedores'));
      }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $compra = new Compras;
        $compra->proveedor_id = $request->proveedorVenta;
        $compra->usuario_id = 1;
        $compra->save();
        foreach ($request->productos as $k => $art) {
          $detalle = new Detallecompras;
          $prod = Productos::where('nombre','=', $art)->first();
          $detalle->producto_id = $prod->id;
          /*$pres = Presentaciones::where('nombre',$request->presentaciones[$k])
                                  ->where('producto_id',$prod->id)
                                  ->first();*/
          $detalle->presentacion_id = $request->presentaciones[$k];
          $detalle->compra_id = $compra->id;
          $detalle->cantidad = $request->cantidades[$k];
          $detalle->iva = $request->ivas[$k];
          $detalle->precio = $request->preciosUnitarios[$k];
          $detalle->save();
        }
        return redirect('/compras')->with('mensaje','Registro Guardado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $c=Compras::find($id);
      $u=Ubicaciones::where('estado',true)->get();
      $detallesCompras=Detallecompras::where('compra_id',$id)->orderBy('producto_id', 'asc')->get();
      return view('Compras.show',compact('detallesCompras','c','u'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ComprasRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function productospresentaciones($nombreProducto){
      $producto=Productos::where('nombre',$nombreProducto)->get();
      foreach ($producto as $p) {
        $idProducto=$p->id;
      }
      $presentaciones=Presentaciones::where('producto_id',$idProducto)->get();
      return Response::json($presentaciones);
    }
    public function precioProductoCompra($nombreProducto,$idPresentacion){
      $producto=Productos::where('nombre',$nombreProducto)->get();
      foreach ($producto as $p) {
        $idProducto=$p->id;
      }
      $presentacion=Presentaciones::where('id',$idPresentacion)->where('producto_id',$idProducto)->get();
      foreach ($presentacion as $p) {
        $ganancia=$p->ganancia;
      }
      return Response::json($ganancia);
    }
    public function nombrePresentacionCompra($presentacion){
      $presentaciones=Presentaciones::where('id',$presentacion)->get();
      foreach ($presentaciones as $p) {
        $nombrePresentacion=$p->nombre;
      }
      return Response::json($nombrePresentacion);
    }
    public function ingresoUbicacion(Request $request){
      $idProducto=$request['producto_id'];
      $ubicacion=$request['ubicacion_id'];
      $idCompra=$request['compra_id'];
      $fechaCaducidad=$request['fechaCaducidad'];
      $presentacion=$request['presentacion'];
      $presentaciones=Presentaciones::where('producto_id',$idProducto)->where('nombre',$presentacion)->get();
      foreach ($presentaciones as $p) {
        $presentacion=$p->id;
      }
      $detalleCompra=Detallecompras::where('compra_id',$idCompra)->where('producto_id',$idProducto)->where('presentacion_id',$presentacion)->update(array('entrega' => true,'ubicacion_id'=>$ubicacion,'fecha_caducidad'=>$fechaCaducidad));
      return Response::json($fechaCaducidad);
    }
}
