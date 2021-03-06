  <?php

  namespace sialas\Http\Controllers;


  use Illuminate\Http\Request;
  use Response;
  use sialas\Http\Requests;
  use sialas\Http\Controllers\Controller;
  use sialas\Productos;
  use sialas\Detalleventas;
  use sialas\Ventas;
  use sialas\Users;
  use DB;
  use sialas\Clientes;
  use sialas\Bitacoras;
  use sialas\Presentaciones;

  class VentasController extends Controller
  {
      /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
       public function index(Request $request)
       {
         $state = $request->get('estado');
         $cajasActivas= Ventas::buscar($state);
         return view('ventas.index',compact('cajasActivas','cajasInactivas','state'));
       }

      /**
       * Show the form for creating a new resource.
       *
       * @return \Illuminate\Http\Response
       */
      public function create()
      {
          $clientes=Clientes::where('estado',true)->orderBy('nombre')->get();
          $productos=Productos::where('estado',true)->orderBy('nombre')->get();
          return view('Ventas.create',compact('clientes','productos'));
      }

      /**
       * Store a newly created resource in storage.
       *
       * @param  \Illuminate\Http\Request  $request
       * @return \Illuminate\Http\Response
       */
      public function store(Request $request)
      {
        Bitacoras::bitacora("Registro de venta");
        $venta = new Ventas;
        $venta->nombre_Cliente = $request->clienteVenta;
        $venta->usuario_id = 1;
        $venta->save();
        foreach ($request->productos as $k => $pro) {
          $detalle = new Detalleventas;
          $prod = Productos::where('nombre','=', $pro)->first();
          $detalle->producto_id = $prod->id;
          $detalle->presentacion_id = $request->presentaciones[$k];
          $detalle->venta_id = $venta->id;
          $detalle->cantidad = $request->cantidades[$k];
          //$detalle->iva = $request->ivas[$k];
          $detalle->precio = $request->preciosUnitarios[$k];
          $detalle->save();
        }
        return redirect('/ventas')->with('mensaje','Registro Guardado');
      }

      /**
       * Display the specified resource.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function show($id)
      {
        $venta=Ventas::find($id);
        return view('Ventas.show',compact('venta'));
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
      public function update(Request $request, $id)
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
      public function llenadoPresentacionesVenta($nombre){
        $producto=Productos::where('nombre',$nombre)->get();
        foreach ($producto as $p) {
          $idProducto=$p->id;
        }
        $presentaciones=Presentaciones::where('producto_id',$idProducto)->get();
        return Response::json($presentaciones);
      }
      public function precioProductoVenta($nombreProducto,$idPresentacion){
        $producto=Productos::where('nombre',$nombreProducto)->get();
        foreach ($producto as $p) {
          $idProducto=$p->id;
        }
        $totalCompra=DB::select('select  (p.equivale*dc.cantidad) as cantidades,dc.precio from detallecompras dc
        inner join presentaciones p on p.id=dc.presentacion_id
        where dc.producto_id='.$idProducto);

        $unidadesSalidas=DB::select('select sum(p.equivale*dc.cantidad) from detalleventas dc
        inner join presentaciones p on p.id=dc.presentacion_id
        where dc.producto_id='.$idProducto);
        foreach ($unidadesSalidas as $us) {
          $unidadesVendidas=$us->sum;
        }
        $sumainterna=0;
        if($totalCompra){
        foreach ($totalCompra as $tc) {
          if($unidadesSalidas==0){
            echo "Primera vez!";
          }else{
          if($unidadesVendidas>=$tc->cantidades && $unidadesVendidas>=$sumainterna){
            echo $unidadesVendidas."es Mayor que".$tc->cantidades."\n";
          }else{
            $sumainterna=$sumainterna+$tc->cantidades;
          }
        }

        }
      }else{return 1704;//NO HAY COMPRAS DEL PRODUCTO}
      $unidadesParaPrecio=$sumainterna-$unidadesVendidas;
      $totalPrecioCompra=DB::select('select sum(dc.precio) from detallecompras dc
  inner join presentaciones p on p.id=dc.presentacion_id
  where dc.producto_id='.$idProducto);
  foreach ($totalPrecioCompra as $tpc) {
    $precioCompraFinal=$tpc->sum;
  }
  $presentacion=Presentaciones::where('id',$idPresentacion)->where('producto_id',$idProducto)->get();
  foreach ($presentacion as $p) {
    $ganancia=$p->ganancia;
  }
  $precioSinGanancia=$precioCompraFinal/$unidadesParaPrecio;
        return Response::json(number_format($precioSinGanancia+$ganancia, 2,'.',''));
    }
      public function nombrePresentacionVenta($presentacion){
        $presentaciones=Presentaciones::where('id',$presentacion)->get();
        foreach ($presentaciones as $p) {
          $nombrePresentacion=$p->nombre;
        }
        return Response::json($nombrePresentacion);
      }
  }
