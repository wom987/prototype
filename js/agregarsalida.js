$(document).ready(function () {
    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::: Cargar selects al iniciar :::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


    cargar_departamento();
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
    :::::::::::::::::::::: Cargar departamentos :::::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    function cargar_departamento(){  
             
        $.ajax({
            type: 'post',
            url: url + 'operaciones/departamentos',    
            success: function (result) {
                var row = $('.detalle').length - 1; 
                //console.log(row);
                $(".detalle").eq(row).find(".select_departamento").html(result);
               // $(".select_departamento").html(result);
                $(".select_departamento").select2();
            }
        }); 
    }

   /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
   :::::::::::::::::::::: Cargar productos :::::::::::::::::::::::::::::
   :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

   function cargar_select_producto(){
    $(".select_producto").select2({
        ajax:{
            url: url+'operaciones/productos_con_saldo',
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
                        text: value['nombre' ]
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
    ::::::::::::::::::::::: Set saldos de productos :::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    function setSaldos(tr, producto_id){       
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: url + 'operaciones/saldo',
            data: {'id': producto_id},            
            success: function (result) {                 
                tr.find('.saldo').val(result.saldo);
            }
        });        
    }


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::: Evento change en select de productos :::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    $('body').on('change', '.select_producto', function(){
        var producto_id = $(this).val();
        var tr = $(this).closest('tr');        
        if(producto_id != ""){ 
            tr.find('input[name="producto[]"]').val(producto_id);
            tr.find('.cantidad').prop( "disabled", false );            
            //No permitir seleccionar el mismo producto
            validar_producto(producto_id,tr); 
            validar_tr(tr);
            //Función para setear saldo del producto seleccionado
            setSaldos(tr,producto_id);                    
            tr.find(".select_producto option").each(function(){             
                if ($(this).val() != producto_id && $(this).val() !="") {
                    $(this).remove();
                }
            })
        }else  {
            tr.find('.producto').val(''); 
            tr.find('.cantidad').val('');      
            tr.find('.cantidad').prop("disabled", true);
            tr.find('.saldo').val('');
            var select_count = $(".select_producto").length;
            //console.log("valor "+select_count);
            //Eliminar tr si se elimina select
            if(select_count > 1){
                tr.remove();
            }
        }    
    });
    
    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::: Evento change en select de departamento ::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    $('body').on('change', '.select_departamento', function(){
        var departamento_id = $(this).val();
        var tr = $(this).closest('tr');        
        if(departamento_id != ""){
            tr.find('input[name="departamento[]"]').val(departamento_id);        
            validar_tr(tr);            
        }   
    });    

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::: Evento change en input de recibe :::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    $('body').on('change', '.recibe', function(){
        var tr = $(this).closest('tr');
        validar_tr(tr);                      
    })

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::: Evento change en input de justificacion :::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    $('body').on('change', '.justificacion', function(){
        var tr = $(this).closest('tr');
        validar_tr(tr);                      
    })

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::: Evento change en input de cantidad :::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    $('body').on('change', '.cantidad', function(){
        var tr = $(this).closest('tr');

        var saldo = parseFloat(tr.find('.saldo').val());
        var cantidad = parseFloat(tr.find('.cantidad').val());                
        
        if (cantidad>saldo) { 
            alerta("Solo hay "+ saldo +" productos disponibles",'warning','fa fa-exclamation-circle');
            tr.find('.cantidad').val(''); 
               
        }
        validar_tr(tr);
        tr.find("#errorfield_cantidad").fadeOut( tiempo, function() {});
        tr.find(this).css("border-color", "");  
    })    

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::: Función para validar que select e inputs :::::::::::::
    ::::::::::::::::::: esten llenos para crear nuevo tr ::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    function validar_tr(tr){
        //Se evalua que los campos de nombre, depto, recibe, justificacion y cantidad esten llenos        
        var bandera = 0;
        $(".select_producto").each(function(){
            if(!($(this).val() != '')){
                bandera = 1;                
            }
        })
        $(".select_departamento").each(function(){
            if(!($(this).val() != '')){
                bandera = 1;                
            }
        })
        $(".cantidad").each(function(){
            if(!($(this).val() != '')){
                bandera = 1; 
            }
        })
        $(".recibe").each(function(){
            if(!($(this).val() != '')){
                bandera = 1;
            }
        })
        $(".justificacion").each(function(){
            if(!($(this).val() != '')){
                bandera = 1;
            }
        })  
        //console.log(cantidad(tr)) ;    
        if(bandera != 1 && cantidad(tr)){
            $(".producto_detalle").append(crear_tr());
            cargar_select_producto();
            cargar_departamento();  
            $(".select_departamento").select2();                       
        }
    } 

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::::::: Validar cantidad ::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    function cantidad(tr){
        var regresar = true;
        if (tr.find('.select_producto').val()!='') {
            if (tr.find(".cantidad").val()==0 || tr.find(".cantidad").val()==".") {
                tr.find("#errorfield_cantidad").html("Ingrese un valor superior a 0");
                tr.find(".cantidad").css("border-color", "red");
                tr.find("#errorfield_cantidad").fadeIn( tiempo, function() {});
                $("#btn_guardar").prop("disabled", true);
                regresar = false; 
            } else{
                $("#btn_guardar").prop("disabled", false);
                regresar = true; 
            }
        }
        return regresar;
    }

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::::: Crear otro tr para productos :::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    function crear_tr(){
        var tr = '<tr class="detalle">'+
                '<td style="width:35% !important;">'+
                '<select name="field_producto" id="field_producto" class="form-control select_producto" data-placeholder="Seleccione" style="width:100% !important;">'+
                '<option value=""></option></select><input type="hidden" name="producto[]" class="producto"></td>'+
                '<td style="width:25% !important;">'+
                '<select name="field_departamento" id="field_departamento" class="form-control select_departamento" data-placeholder="Seleccione" style="width:100% !important;">'+
                '<option value=""></option></select><input type="hidden" name="departamento[]" class="departamento"></td>'+
                '<td><input name="recibe[]" class="form-control recibe"></td>'+
                '<td><textarea name="justificacion[]" class="form-control justificacion"></textarea></td>'+               
                '<td style="width:12%"><input name="cantidad[]" disabled="true" class="form-control cantidad">'+
                '<input type="hidden" class="form-control saldo"><label id="errorfield_cantidad" class="error" style="display:none;"></td>'+            
                '</tr>';
        return tr;
    }



    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::: Validar que no se seleccione el mismo producto :::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    function validar_producto(valor, tr){
        var opcion = $('.select_producto option[value="'+valor+'"]').length;
        if(opcion > 1){
            tr.find('.select_producto').html('<option value=""><option>');
            tr.find('.producto').val('');
            if($(".producto[value='']").length > 2){
                tr.remove();
            } else{
                cargar_select_producto();
                //tr.find(".select_cantidad").html("");
                alerta("El producto ya ha sido seleccionado",'success','fas fa-times-circle');
                tr.find('.cantidad').prop( "disabled", true );
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

        var buenas = 0;
        var malas = 0;
        $(".producto_detalle .detalle").each(function(){
            if ($(this).find('.select_producto').val()=='' || $(this).find('.select_departamento').val()=='' || $(this).find('.recibe').val()=='' || $(this).find('.justificacion').val()=='' || $(this).find('.cantidad').val()=='') {
            } else{
                buenas = 1;
            }
        })
        $(".producto_detalle .detalle").each(function(){
            if ($(this).find('.select_producto').val()=='' && $(this).find('.select_departamento').val()=='' && $(this).find('.recibe').val()=='' && $(this).find('.justificacion').val()=='' && $(this).find('.cantidad').val()=='') {
                if(buenas != 1){
                    malas = 1;
                }
            } else if ($(this).find('.select_producto').val()=='' || $(this).find('.select_departamento').val()=='' || $(this).find('.recibe').val()=='' || $(this).find('.justificacion').val()=='' || $(this).find('.cantidad').val()=='') {
                if(buenas == 1){
                    if ($(this).find('.select_producto').val()=='' || $(this).find('.select_departamento').val()=='' || $(this).find('.recibe').val()=='' || $(this).find('.justificacion').val()=='' || $(this).find('.cantidad').val()==''){
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

        return regresar;
    }

    $("#field_fecha").change(function(){
        $("#errorfield_fecha").fadeOut( tiempo, function() {});
        $("#field_fecha").css("border-color", "");
    });
     

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::::::::::: Solo números ::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/ 
    
    $('body').on('keypress keyup blur', '.cantidad', function(){ 
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
            $("#frm_salida").submit();
        }
    })    
});