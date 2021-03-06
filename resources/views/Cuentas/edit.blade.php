@extends('welcome')
@section('layout')
	<div class="launcher">
	    <div class="lfloat"></div>
	    <div class="tooltip">
	      <a href={!! asset('/cuentas') !!}>
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
			<h2>Cuentas</h2>
			<h3 id='txt'> |Editar</h3>
		</div>
	{!!Form::model($c,['route'=>['cuentas.update',$c->id],'method'=>'PUT'])!!}
		<div class="alerta-errores">
			@foreach ($errors->get('cuenta') as $error)
				<br>{{$error}}
			@endforeach
		</div>
		{!!Form::label('LNombre','Nombre de la cuenta:')!!}
		{!!Form::text('cuenta',null,['placeholder'=>'Ingrese el nombre','focusable'])!!}
	{!!Form::submit('Guardar')!!}

	{!!Form::close()!!}
	</div>

@stop
