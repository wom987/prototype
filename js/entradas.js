$(document).ready(function () { 
    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::: Cargar selects al iniciar :::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/    
    cargar_categorias(); 
    cargar_productos();      
    
    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::: Cargar datepicker :::::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    $(".date").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayHighlight: true    
    }).datepicker("setDate", new Date());    
    

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::: Cargar categorías :::::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    function cargar_categorias(){        
        $.ajax({
            type: 'post',
            url: url + 'consultas/categorias',           
            success: function (result) {
                $("#field_categoria").html(result);
                $("#field_categoria").select2();
            }
        }); 
    }

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::: Cambiar select producto x categoría ::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    $(".cargar_producto").change(function(){
        var categoria = $("#field_categoria").val();       
        $.ajax({
            type: 'post',
            url: url + 'consultas/productos',
            data: {'categoria': categoria},            
            success: function (result) {
                $("#field_producto").html(result);
                $("#field_producto").select2();
            }
        });  
    })

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::: Cambiar select producto x categoría ::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    function cargar_productos(){ 
        $.ajax({
            type: 'post',
            url: url + 'consultas/todos_productos',                       
            success: function (result) {
                $("#field_producto").html(result);
                $("#field_producto").select2();
            }
        });  
    }    

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::::::::::: Iniciar búsqueda :::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    $("#btn_search").click(function(){ 
        if (requeridos()) {
            if (validar_fechas()) {                
                var field_desde = formato_fechas($("#field_desde").val()); 
                var field_hasta = formato_fechas($("#field_hasta").val());                 
                var field_categoria = $("#field_categoria").val();
                var field_producto = $("#field_producto").val();       
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: url + 'consultas/reporte_entradas',
                    data: {'field_desde': field_desde,'field_hasta':field_hasta, 'field_categoria': field_categoria,'field_producto':field_producto},            
                    success: function (result) {
                        $('#resultado').fadeIn( "slow", function() {});                                         
                        var tabla = ""; 
                        if (result.length==0) {
                            tabla += '<tr>'+
                            '<td colspan = "6" align="center">0 entradas realizadas</td>'+ 
                            '</tr>';
                        }                        
                        tabla += result; 
                        //console.log(tabla);                                        
                        $("#reporte").html(tabla);
                        $("#html").val(tabla);                        
                    }
                }); 
            } 
        }
    })


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::::::::: Formatear fechas ::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    function formato_fechas(fecha){
        var fecha = fecha.split('/');
        fecha = fecha[2]+'/'+fecha[1]+'/'+fecha[0]; //yy/mm/dd
        return fecha;
    }
    

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::::: Validar fechas - desde<hasta :::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    function validar_fechas(){
        var field_desde = $("#field_desde").val();
        var field_hasta = $("#field_hasta").val();
        var regresar = true;
        if(($.trim(field_desde) == "" || $.trim(field_hasta) == "")){
            regresar = false;            
        }
        
        var field_desde_array = field_desde.split('/');
        var field_hasta_array = field_hasta.split('/');

        var fecha1 = field_desde_array[2]+'-'+field_desde_array[1]+'-'+field_desde_array[0];
        var fecha2 = field_hasta_array[2]+'-'+field_hasta_array[1]+'-'+field_hasta_array[0];
        var fechaInicio = new Date(fecha1).getTime();
        var fechaFin    = new Date(fecha2).getTime();

        var diff = fechaFin - fechaInicio;

        var diferencia = diff/(1000*60*60*24);
        if(diferencia < 0 && regresar){
            regresar = false;
            $("#errorfield_desde").html("Verifique las fechas");
            $("#field_hasta").css("border-color", "red");
            $("#errorfield_hasta").html("Verifique las fechas");
            $("#field_desde").css("border-color", "red");
            $("#errorfield_desde").fadeIn( 1000, function() {});
            $("#errorfield_hasta").fadeIn( 1000, function() {});
            $("#field_desde").val("");
            $("#field_hasta").val("") ;         
        }
        return regresar;
    }   

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::::::::::: Campos requeridos ::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/  
    tiempo = 1000;  
    function requeridos(){
        var regresar = true;        
        if ($("#field_hasta").val()=='') {
            $("#errorfield_hasta").html("Campo requerido");
            $("#field_hasta").css("border-color", "red");
            $("#errorfield_hasta").fadeIn( tiempo, function() {});
            regresar = false;
        }
        if ($("#field_desde").val()=='') {
            $("#errorfield_desde").html("Campo requerido");
            $("#field_desde").css("border-color", "red");
            $("#errorfield_desde").fadeIn( tiempo, function() {});
            regresar = false;
        }
        return regresar;
    } 

    $("#field_desde").change(function(){
        $("#errorfield_desde").fadeOut( tiempo, function() {});
        $("#field_desde").css("border-color", "");
    });  
    $("#field_hasta").change(function(){
        $("#errorfield_hasta").fadeOut( tiempo, function() {});
        $("#field_hasta").css("border-color", "");
    }); 


    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::::::::: Generar PDF :::::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/ 
    $('#btn_pdf').click(function(){
        $("#data").submit();
    })
});