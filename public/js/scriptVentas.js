    function addVenta(){
      var correlativo=$("#correlativoVenta").val();
      var total=$("#inputTotalVenta").val();
      var articulo=$("#articulos").val();
      var precioUnitario=$("#precioUnitario").val();
      var presentacion=$("#selectPresentacionesVenta").find('option:selected').val();
      if(articulo==0){
        $("#cantidadArticuloVenta").val("");
        $("#existenciasActualesArticulos").val("");
        $("#precioUnitario").val("");
        return swal("Debe seleccionar un artículo", "No Procesado!", "info");
      }
      else{
       var cantidad=parseInt($("#cantidadArticuloVenta").val());
       var tablaDatos= $("#tblDatosVenta");
       if(cantidad==""||!/^([0-9])*$/.test(cantidad)){
        swal({   title: 'La cantidad no es un numero\n o No es permitido',type:'error',  text: 'Se Cerrará en 2 Segundos',   timer: 2700,   showConfirmButton: false });
      }else{
        correlativo=parseInt(correlativo)+1;
        total=parseFloat(total)+parseFloat(cantidad*precioUnitario);
        tablaDatos.append("<tr><td>"+
        parseInt(cantidad)+
        "</td><td>"+presentacion+
        "</td><td><input type='hidden' name='productos[]' value='"+
        articulo+"'/><input type='hidden' name='preciosUnitarios[]' value='"+
        parseFloat(precioUnitario).toFixed(2)+
        "'/><input type='hidden' name='cantidades[]' value='"+parseInt(cantidad)+"'/>"
        +articulo+
        "</td><td>"+parseFloat(precioUnitario).toFixed(2)+
        "</td><td>"+parseFloat(precioUnitario*cantidad).toFixed(2)+
        "</td><td class='eliminarVenta' style='cursor:pointer;'>Eliminar</td></tr>");
        document.getElementById("correlativoVenta").value=correlativo;
        document.getElementById("inputArticulosVenta").value=correlativo;
        document.getElementById("inputTotalVenta").value=total.toFixed(2);
        reset_camposVenta();
      }
    }
}

function reset_camposVenta(){
 $("#cantidadArticuloVenta").val("");
 $("#precioUnitario").val("");
}
$(document).on("click",".eliminarVenta",function(){
  var totalFila=parseFloat($(this).parents('tr').find('td:eq(4)').html());
  var cantidadEliminar=$(this).parents('tr').find('td:eq(0)').html();
  var parent = $(this).parents().get(0);
  $(parent).remove();
  var correlativo=$("#correlativoVenta").val();
  var total=parseFloat($("#inputTotalVenta").val());
  total=total-(totalFila);
  correlativo=parseInt(correlativo)-1;
  document.getElementById("correlativoVenta").value=correlativo;
  document.getElementById("inputArticulosVenta").value=correlativo;
  document.getElementById("inputTotalVenta").value=total.toFixed(2);
});

function submitar(){
  document.forms["formVenta"].submit();
  $("#inputArticulosVenta").val("0");
  $("#inputTotalVenta").val("0");
}
