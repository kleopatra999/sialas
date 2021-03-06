@extends('welcome')
@section('layout')
  <div class="launcher">
    <div class="lfloat"></div>
    <div class="tooltip">
      <a href={!! asset('/ubicaciones') !!}>
        <img src={!! asset('/img/WB/atr.svg') !!} alt="" class="circ"/>
      </a>
      <span class="tooltiptext">Atras</span>
    </div>
    <div class="tooltip">
      <a href="#">
        <img src={!! asset('/img/WB/ayu.svg') !!} alt="" class="circ"/>
      </a>
      <span class="tooltiptext">Ayuda</span>
    </div>
  </div>
  <div class="panel">
    <div class="enc">
      <h2>Ubicaciones</h2>
    </div>
{!! Form::open(['route'=>'ubicaciones.store','method'=>'POST'])!!}
@include('ubicaciones.Formularios.formulario')
{!!Form::submit('Guardar')!!}
{!!Form::close()!!}
</div>
@stop
