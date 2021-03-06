    /* INICIO JAVASCRIPT DE COMPRAS*/
    function enterCompras(evento){
      var char= event.which || event.keyCode;
      var producto=$("#articulos").val();
      if(char==13){
      var presentaciones=$("#selectPresentacionesCompra");
    	var ruta="/sialas/public/productospresentaciones/"+producto;
    	$.get(ruta,function(res){
    		presentaciones.empty();
    		$(res).each(function(key,value){
    			presentaciones.append("<option value='"+value.id+"'>"+value.nombre+"</option>");
    		});
    	});
      }
    }


        function addProductoCompra(){
          var correlativo=$("#correlativoVenta").val();
          var total=$("#inputTotalCompra").val();
          //
      //var promedio=$("#inputPromedioProductosVenta").val();

          var articulo=$("#articulos").val();
          var precioUnitario=$("#precioUnitarioCompra").val();
          var iva=$("#ivavalor").val();
          var presentacion=$("#selectPresentacionesCompra").find('option:selected').val();
          if(articulo==0){
            $("#cantidadArticuloCompra").val("");
            $("#existenciasActualesArticulos").val("");
            $("#precioUnitario").val("");
            $("#ivavalor").val("");
            return swal("Debe seleccionar un artículo", "No Procesado!", "info");
          }
          else{
           var cantidad=parseInt($("#cantidadArticuloCompra").val());
           var tablaDatos= $("#tblDatosCompra");
           if(cantidad==""||!/^([0-9])*$/.test(cantidad)){
            swal({   title: 'La cantidad no es un numero\n o No es permitido',type:'error',  text: 'Se Cerrará en 2 Segundos',   timer: 2700,   showConfirmButton: false });
          }else{
            correlativo=parseInt(correlativo)+1;
            total=parseFloat(total)+parseFloat(cantidad*precioUnitario)+parseFloat(iva);
            subtotal = parseFloat(cantidad*precioUnitario)+parseFloat(iva);
            var ruta="/sialas/public/nombrePresentacionCompra/"+presentacion;
            $.get(ruta,function(res){
              var presentationNameSell=res;
            tablaDatos.append("<tr><td>"+
            parseInt(cantidad)+
            "</td><td>"+presentationNameSell+
            "</td><td><input type='hidden' name='productos[]' value='"+
            articulo+"'/><input type='hidden' name='preciosUnitarios[]' value='"+
            parseFloat(precioUnitario).toFixed(2)+
            "'/><input type='hidden' name='presentaciones[]' value='"+presentacion+
            "'/><input type='hidden' name='cantidades[]' value='"+parseInt(cantidad)+
            "'/><input type='hidden' name='ivas[]' value='"+parseFloat(iva).toFixed(2)+"'/>"
            +articulo+
            "</td><td>"+parseFloat(precioUnitario).toFixed(2)+
            "</td><td>"+parseFloat(iva).toFixed(2)+
            "</td><td>"+parseFloat(subtotal).toFixed(2)+
            "</td><td class='eliminarVenta' style='cursor:pointer;'>Eliminar</td></tr>");
          });
            document.getElementById("correlativoVenta").value=correlativo;
            document.getElementById("inputArticulosVenta").value=correlativo;
            document.getElementById("inputTotalCompra").value=total.toFixed(2);
            reset_camposVenta();
          }
        }
    }

    function reset_camposVenta(){
     $("#cantidadArticuloCompra").val("");
     $("#precioUnitarioCompra").val("");
     $("#articulos").val("");
     $("#selectPresentacionesCompra").val("");
     $("#ivavalor").val("");
    }
    $(document).on("click",".eliminarVenta",function(){
      var totalFila=parseFloat($(this).parents('tr').find('td:eq(5)').html());
      var cantidadEliminar=$(this).parents('tr').find('td:eq(0)').html();
      var parent = $(this).parents().get(0);
      $(parent).remove();
      var correlativo=$("#correlativoVenta").val();
      var total=parseFloat($("#inputTotalCompra").val());
      //
      var promedio=parseFloat($("#inputPromedioProductosVenta").val());

      total=total-(totalFila);
      correlativo=parseInt(correlativo)-1;
      document.getElementById("correlativoVenta").value=correlativo;
      document.getElementById("inputArticulosVenta").value=correlativo;
      document.getElementById("inputTotalCompra").value=total.toFixed(2);
    });

    function registrarCompra(){
      document.forms["formSell"].submit();
      $("#inputArticulosCompra").val("0");
      $("#inputTotalCompra").val("0");
    }

    $(document).on("click",".agrUbi",function(){
      var idProducto=$(this).parents('tr').find('td:eq(0)').html();
      var presentacion=$(this).parents('tr').find('td:eq(2)').html();
      var idVenta=$('#venta_id').val();
      document.getElementById("temporalProducto").value=idProducto;
      document.getElementById("temporalPresentacion").value=presentacion;
      document.getElementById('modalUbi').click();
    });

    function addUbicacion(e){
      var ubicacion = $("#selectUbicaciones").val();
      var idProducto = $("#temporalProducto").val();
      var presentacion = $("#temporalPresentacion").val();
      var idCompra = $("#compra_id").val();
      var caducidad= $('#dateCaducidad').val()
        var ruta="/sialas/public/ingresoUbicacion";
      	var token=$('#token').val();
      	$.ajax({
      		url:ruta,
      		headers:{'X-CSRF-TOKEN':token},
      		type:'POST',
      		dataType:'json',
      		data:{producto_id:idProducto,compra_id:idCompra,ubicacion_id:ubicacion,presentacion:presentacion,fechaCaducidad:caducidad}
      	});
        window.setTimeout('location.reload()', 500);
    };
    function habilitarCaducidad(e){
      if($('#checkCaducidad:checked').val()==1){
      $('#dateCaducidad').removeAttr("disabled");
    }else{
      $('#checkCaducidad').value=0;
      $('#dateCaducidad').attr('disabled','disabled');
    }
    }
    /* FIN  JAVASCRIPT DE COMPRAS*/

    /* INICIO JAVASCRIPT DE VENTAS*/
    function enter(e){
      var char= event.which || event.keyCode;
      var producto=$("#productos").val();
      if(char==13){
      var presentaciones=$("#selectPresentacionesVenta");
    	var ruta="/sialas/public/llenadoPresentacionesVenta/"+producto;
    	$.get(ruta,function(res){
    		presentaciones.empty();
    		$(res).each(function(key,value){
    			presentaciones.append("<option value='"+value.id+"'>"+value.nombre+"</option>");
    		});
    	});
      }
    }
    function obtenerPrecioProducto(){
      var producto=$("#productos").val();
      var presentacion=$("#selectPresentacionesVenta").val();
      var ruta="/sialas/public/precioProductoVenta/"+producto+"/"+presentacion;
      var precio=$("#precioProductoUnitario").val();
      $.get(ruta,function(res){
        $("#precioProductoUnitario").val("0.00");
        if(res=="1704"){
          swal({   title: 'No hay compras del producto',type:'error',  text: 'Se Cerrará en 2 Segundos',   timer: 2700,   showConfirmButton: false });
        }else{
        $("#precioProductoUnitario").val(res);
        }
      });
    }
    function agregarProductoVenta(){
      var producto=$("#productos").val();
      var correlativo=$("#correlativoHiddenVenta").val();
      var total=$("#inputTotalProductosVenta").val();
      //
      var promedio=$("#inputPromedioProductosVenta").val();

      var precioProducto=$("#precioProductoUnitario").val();
      var iva=$("#ivavalor").val();
      var presentacion=$("#selectPresentacionesVenta").find('option:selected').val();
      if(producto==0){
        $("#cantidadProductoVenta").val("");
        $("#existenciasActualesArticulos").val("");
        $("#precioProductoUnitario").val("");
        $("#ivavalor").val("");
        return swal("Debe seleccionar un artículo", "No Procesado!", "info");
      }
      else{
       var cantidad=parseInt($("#cantidadProductoVenta").val());
       var tablaDatos= $("#tblProductosVenta");
       if(cantidad==""||!/^([0-9])*$/.test(cantidad)){
        swal({   title: 'La cantidad no es un numero\n o No es permitido',type:'error',  text: 'Se Cerrará en 2 Segundos',   timer: 2700,   showConfirmButton: false });
      }else{
        correlativo=parseInt(correlativo)+1;
        total=parseFloat(total)+parseFloat(cantidad*precioProducto);//+parseFloat(iva)
        subTotalVentas = parseFloat(cantidad*precioProducto);//+parseFloat(iva)
        var ruta="/sialas/public/nombrePresentacionVenta/"+presentacion;
        $.get(ruta,function(res){
          var presentationName=res;
        tablaDatos.append("<tr><td>"+
        parseInt(cantidad)+
        "</td><td>"+presentationName+
        "</td><td><input type='hidden' name='productos[]' value='"+
        producto+"'/><input type='hidden' name='preciosUnitarios[]' value='"+
        parseFloat(precioProducto).toFixed(2)+
        "'/><input type='hidden' name='presentaciones[]' value='"+presentacion+
        "'/><input type='hidden' name='cantidades[]' value='"+parseInt(cantidad)+
        "'/>"
        +producto+
        "</td><td>"+parseFloat(precioProducto).toFixed(2)+
        "</td><td>"+parseFloat(subTotalVentas).toFixed(2)+
        "</td><td class='deleteBuy' style='cursor:pointer;'>Eliminar</td></tr>");
        });
        document.getElementById("correlativoHiddenVenta").value=correlativo;
        document.getElementById("inputProductosVenta").value=correlativo;
        document.getElementById("inputTotalProductosVenta").value=total.toFixed(2);
        reiniciarCamposVenta();
      }
    }
    }
  /* DESCRIPCION DE IVA
  <td>"+parseFloat(iva).toFixed(2)+
  "</td>
  <input type='hidden' name='ivas[]' value='"+parseFloat(iva).toFixed(2)+"'/>*/
  function Registro2(){
    document.forms["formBuy"].submit();
    $("#inputProductosVenta").val("0");
    $("#inputTotalProductosVenta").val("0");
  }
    function reiniciarCamposVenta(){
    $("#cantidadProductoVenta").val("");
    $("#precioProductoUnitario").val("");
    $("#productos").val("");
    $("#selectPresentacionesVenta").val("");
    $("#ivavalor").val("");
    }
    $(document).on("click",".deleteBuy",function(){
    var totalFila=parseFloat($(this).parents('tr').find('td:eq(4)').html());
    var cantidadEliminar=$(this).parents('tr').find('td:eq(0)').html();
    var parent = $(this).parents().get(0);
    $(parent).remove();
    var correlativo=$("#correlativoHiddenVenta").val();
    var total=parseFloat($("#inputTotalProductosVenta").val());
    total=total-(totalFila);
    correlativo=parseInt(correlativo)-1;
    document.getElementById("correlativoHiddenVenta").value=correlativo;
    document.getElementById("inputProductosVenta").value=correlativo;
    document.getElementById("inputTotalProductosVenta").value=total.toFixed(2);
    });
    /* FIN JAVASCRIPT DE VENTAS*/
