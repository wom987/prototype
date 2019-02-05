$(document).ready(function () { 
    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::: Cargar selects al iniciar :::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/ 

    cargar_proveedores(); 
    cargar_select_producto();  


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::: Cargar datepicker :::::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    $(".date").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayHighlight: true    
    }).datepicker("setDate", new Date());    
    

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::::::: Cargar proveedores :::::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    function cargar_proveedores(){        
        $.ajax({
            type: 'post',
            url: url + 'operaciones/proveedores',           
            success: function (result) {
                $("#field_proveedor").html(result);
                $("#field_proveedor").select2();
            }
        }); 
    }

    
    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::::::: Cargar productos :::::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/    

    function cargar_select_producto(){
        $(".select_producto").select2({
            ajax:{
                url: url+'operaciones/productos', 
                dataType: 'json',
                headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                delay: 250,
                type: 'POST',
                data: function (params) {
                    return {
                        buscar: params.term, // search term
                    };
                },
                processResults: function (data) {
                    var result = []
                    $.each(data, function (index, value) {
                        result.push({
                            id: value['id'],
                            text: value['nombre']
                        })
                    })
                    return {
                        results: result
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; },
            minimumInputLength: 2
        })
    }  


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::: Evento change en select de productos :::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    $('body').on('change', '.select_producto', function(){
        var producto_id = $(this).val();
        var tr = $(this).closest('tr');
        //console.log(producto_id);
        if(producto_id != ""){
            tr.find('input[name="producto[]"]').val(producto_id);
            validar_tr();
            //No permitir seleccionar el mismo producto
            validar_producto(producto_id,tr);                     
            tr.find(".select_producto option").each(function(){             
                if ($(this).val() != producto_id && $(this).val() !="") {
                    $(this).remove();
                }
            })
        }else  {              
            tr.find('.producto').val('');         
            tr.find('.cantidad_producto').val('');
            tr.find('.precio_producto').val('');
            totalizar_producto(tr)
            total_productos(); 
            var select_count = $(".select_producto").length;
            //console.log("valor "+select_count);
            //Eliminar tr si se elimina select
            if(select_count > 1){
                tr.remove();           
            }            
        }
    });     


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::: Evento change en input de cantidad :::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    $('body').on('change', '.cantidad_producto', function(){
        var tr = $(this).closest('tr');
        validar_tr();
        totalizar_producto(tr);       
        total_productos();                   
    })      


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::: Evento keyup en input de precio ::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/ 
    
    $('body').on('change', '.precio_producto', function(){
        var tr = $(this).closest('tr');        
        var num = Number(tr.find('.precio_producto').val()); 
        var roundedString = num.toFixed(2);
        var rounded = Number(roundedString);
        tr.find('.precio_producto').val(rounded);          
        totalizar_producto(tr);       
        total_productos(); 
        validar_tr();  
    })    


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::: Función para totalizar un producto ::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    function totalizar_producto(tr){
        var cantidad = tr.find('.cantidad_producto').val();
        var precio = tr.find('.precio_producto').val();
        //console.log(cantidad);
        if(cantidad != "" && precio != ""){
            var resultado = parseFloat(cantidad) * parseFloat(precio);
            resultado = resultado || 0;
            resultado = resultado.toFixed(2);            
            tr.find('.totalizar_producto').val(resultado);
        } else{
            tr.find('.totalizar_producto').val(0.00);
        }
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::: Función para totalizar todos los productos ::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    function total_productos(){
        var total = 0;    
        $(".totalizar_producto").each(function(){       
            if(($(this).val() != '')){
                var total_temp = parseFloat($(this).val());            
                total += total_temp;    
                //console.log(total);   
            }         
        })
        $("#cantidad_total").val(total.toFixed(2));  
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::: Función para validar que select e inputs :::::::::::::
    ::::::::::::::::::: esten llenos para crear nuevo tr ::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    function validar_tr(){
    //Se evalua que los campos de nombre, precio y cantidad esten llenos
    var bandera = 0;
    $(".select_producto").each(function(){
        if(!($(this).val() != '')){
            bandera = 1;
        }
    })
    $(".cantidad_producto").each(function(){
        if(!($(this).val() != '')){
            bandera = 1;
        }
    })
    $(".precio_producto").each(function(){
        if(!($(this).val() != '')){
            bandera = 1;
        }
    })
    if(bandera != 1){
     $(".producto_detalle").append(crear_tr());            
     cargar_select_producto();
 }        
}


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::::: Crear otro tr para productos :::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    function crear_tr(){        
        var tr = '<tr class="detalle">'+       
        '<td style="width:35% !important;">'+
        '<select name="field_producto" id="field_producto" class="form-control select_producto" data-placeholder="Seleccione" style="width:100% !important;">'+
        '<option value=""></option>'+
        '</select><input type="hidden" name="producto[]" class="producto" value=""></td>'+
        '<td><input name="field_cantidad[]" class="form-control cantidad_producto numero" maxlength="10"><label id="errorfield_cantidad" class="error" style="display:none;"></label></td>'+
        '<td><input name="field_precio[]" class="form-control precio_producto numero" maxlength="10"><label id="errorfield_precio" class="error" style="display:none;"></label></td>'+
        '<td><input name="field_total[]" class="form-control totalizar_producto" readonly></td>'+
        '</tr>';              
        return tr;        
    }  


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::: Validar que no se seleccione el mismo producto :::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    function validar_producto(valor, tr){
        var opcion = $('.select_producto option[value="'+valor+'"]').length;
        //console.log(opcion);        
        if(opcion > 1){
            tr.find('.select_producto').html('<option value><option>');
            tr.find('.producto').val('');            
            if($(".producto[value='']").length > 2){
                tr.remove();
            } else{
                cargar_select_producto();
                alerta("El producto ya ha sido seleccionado",'success','fas fa-times-circle');
            }
        }
    }    

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::::::::::: Campos requeridos ::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/  

    tiempo = 1000;
    function requeridos(){
        var regresar = true;
        var registro = 0;       
        if ($("#field_fecha").val()=='') {
            $("#errorfield_fecha").html("Campo requerido");
            $("#field_fecha").css("border-color", "red");
            $("#errorfield_fecha").fadeIn( tiempo, function() {});
            regresar = false;
        }
        if ($("#field_factura").val()=='') {
            $("#errorfield_factura").html("Campo requerido");
            $("#field_factura").css("border-color", "red");
            $("#errorfield_factura").fadeIn( tiempo, function() {});
            regresar = false;
        }
        if ($("#field_proveedor").val()=='') {
            $("#errorfield_proveedor").html("Campo requerido");
            $("#field_proveedor").css("border-color", "red");
            $("#errorfield_proveedor").fadeIn( tiempo, function() {});
            regresar = false;
        }        

        var buenas = 0;
        var malas = 0;
        $(".producto_detalle .detalle").each(function(){ 
            if ($(this).find('.select_producto').val()=='' || $(this).find('.cantidad_producto').val()=='' || $(this).find('.precio_producto').val()=='') {
            } else{
                buenas = 1;
            }
        })
        $(".producto_detalle .detalle").each(function(){ 
            if ($(this).find('.select_producto').val()=='' && $(this).find('.cantidad_producto').val()=='' && $(this).find('.precio_producto').val()=='') {
                if(buenas != 1){
                    malas = 1;                  
                }
            } else if ($(this).find('.select_producto').val()=='' || $(this).find('.cantidad_producto').val()=='' || $(this).find('.precio_producto').val()=='') {
                if(buenas == 1){
                    if ($(this).find('.select_producto').val()=='' || $(this).find('.cantidad_producto').val()=='' || $(this).find('.precio_producto').val()==''){
                        malas = 1; 
                    }
                } else{
                    malas = 1;                  
                }
            } else{
                buenas = 1;
            }
        })

        if(malas == 1){
            alerta("El detalle del producto es requerido","danger",'fas fa-times');
            regresar= false;                       
        }

        $(".producto_detalle .detalle").each(function(){ 
            if ($(this).find('.select_producto').val()!=''){
                if ($(this).find(".precio_producto").val()==0 || $(this).find(".precio_producto").val()==".") {
                    $(this).find("#errorfield_precio").html("Ingrese un valor superior a 0");
                    $(this).find(".precio_producto").css("border-color", "red");
                    $(this).find("#errorfield_precio").fadeIn( tiempo, function() {});
                    regresar = false;
                }  

                if ($(this).find(".cantidad_producto").val()==0 || $(this).find(".cantidad_producto").val()==".") {
                    $(this).find("#errorfield_cantidad").html("Ingrese un valor superior a 0");
                    $(this).find(".cantidad_producto").css("border-color", "red");
                    $(this).find("#errorfield_cantidad").fadeIn( tiempo, function() {});
                    regresar = false;
                }
            }
        })
        return regresar;
    }    


    $("#field_fecha").change(function(){
        $("#errorfield_fecha").fadeOut( tiempo, function() {});
        $("#field_fecha").css("border-color", "");
    });  
    $("#field_factura").change(function(){
        $("#errorfield_factura").fadeOut( tiempo, function() {});
        $("#field_factura").css("border-color", "");
    }); 
    $("#field_proveedor").change(function(){
        $("#errorfield_proveedor").fadeOut( tiempo, function() {});
        $("#field_proveedor").css("border-color", "");
    });

    $("body").on("change",".cantidad_producto", function(){
        $(this).closest("tr").find("#errorfield_cantidad").fadeOut( tiempo, function() {});
        $(".cantidad_producto").css("border-color", "");
    })

    $("body").on("change",".precio_producto", function(){
        $(this).closest("tr").find("#errorfield_precio").fadeOut( tiempo, function() {});
        $(".precio_producto").css("border-color", "");
    })

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::::::::::: Solo números ::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/ 
    $('body').on('keypress keyup blur', '.numero', function(){ 
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });     

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::::: Guardar formulario ::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/ 

    $("#btn_guardar").click(function(){
        if(requeridos()){            
            $("#btn_guardar").prop( "disabled", true );
            $("#frm_entrada").submit();                       
        }
    })        
});