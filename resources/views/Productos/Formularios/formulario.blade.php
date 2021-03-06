<?php
if($bandera==1){
	$valorc=null;
	$valorm=null;
}else{
	$valorc=$productos->categoria_id;
	$valorm=$productos->marca_id;
}
 ?>
 <div class="fila">
 	<div>
 		<div class="alerta-errores">
			@foreach ($errors->get('nombre') as $error)
				<br>{{$error}}
			@endforeach
		</div>
		{!!Form::label('lnombre','Nombre:')!!}
		{!!Form::text('nombre',null,['placeholder'=>'Nombre del producto'])!!}
		{!!Form::label('lmarca','Marca:')!!}
		<select name="marca_id">
			@foreach($m as $marcas)
				@if($valorm==$marcas->id && $valorm!=null)
					<option value="{{$marcas->id}}" selected="selected">{{$marcas->nombre}}</option>
				@else
					<option value="{{$marcas->id}}">{{$marcas->nombre}}</option>
				@endif
			@endforeach
		</select>
		<div class="alerta-errores">
			@foreach ($errors->get('nombre_img') as $error)
				<br>{{$error}}
			@endforeach
		</div>
		{!!Form::label('limagen','Imagen:')!!}
		{!!Form::file('nombre_img',['value'=>'Elija'])!!}
 	</div>
	<div>
		<div class="alerta-errores">
			@foreach ($errors->get('codigo_barra') as $error)
				<br>{{$error}}
			@endforeach
		</div>
		{!!Form::label('lcodigo','Código de barra:')!!}
		{!!Form::text('codigo_barra',null,['placeholder'=>'Código de barra'])!!}
		{!!Form::label('lcategoria','Categoría:')!!}
		<select name="categoria_id">
			@foreach($c as $cate)
				@if($valorc==$cate->id && $valorc!=null)
					<option value="{{$cate->id}}" selected="selected">{{$cate->nombre}}</option>
				@else
					<option value="{{$cate->id}}">{{$cate->nombre}}</option>
				@endif
			@endforeach
		</select>
	</div>
 </div>
