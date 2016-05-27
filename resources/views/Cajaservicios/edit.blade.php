@extends('welcome')
<?php $bandera=0; ?>
@section('layout')
  <div class="launcher">
    <div class="lfloat"></div>
    <div class="tooltip">
      <a href={!! asset('/cajaservicios') !!}>
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
      <h2>Pagos Servicios</h2>
      <h3 id="txt">|Editar</h3>
    </div>
{!! Form::model($cajaservicios, ['route'=> ['cajaservicios.update', $cajaservicios->id],'method'=>'PUT']) !!}
@include('cajaservicios.Formularios.formulario')
{!! Form:: submit('Actualizar') !!}
{!! Form::close() !!}
</div>
@stop
