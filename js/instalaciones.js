$(document).ready(function () { 
    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::: Cargar selects al iniciar :::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/    
    cargar_departamentos();     
    
    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::: Cargar datepicker :::::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    $(".date").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayHighlight: true    
    }).datepicker("setDate", new Date());    
    

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::::::: Cargar departamentos :::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    function cargar_departamentos(){        
        $.ajax({
            type: 'post',
            url: url + 'consultas/departamentos',           
            success: function (result) {
                $("#field_departamento").html(result);
                $("#field_departamento").select2();
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
                var field_departamento = $("#field_departamento").val();
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: url + 'consultas/reporte_instalaciones',
                    data: {'field_desde': field_desde,'field_hasta':field_hasta, 'field_departamento': field_departamento},            
                    success: function (result) {
                        $('#resultado').fadeIn( "slow", function() {});
                        var tabla = ""; 
                        if (result.length==0) {
                            tabla += '<tr>'+
                            '<td colspan = "9" align="center">0 instalaciones realizadas</td>'+ 
                            '</tr>';
                        }                                             
                        /*for (var x in result) {
                            tabla += '<tr>'+                            
                            '<td>'+result[x].de_nombre+'</td>'+
                            '<td>'+result[x].pr_codigo+'</td>'+
                            '<td>'+result[x].pr_nombre+'</td>'+
                            '<td>'+result[x].do_cantidad+'</td>'+
                            '<td>'+result[x].do_descripcion+'</td>'+
                            '<td>'+result[x].do_recibe+'</td>'+
                            '<td>'+result[x].fecha+'</td>'+
                            '</tr>';        
                        }*/
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
    tiempo= 1000;
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
            $("#errorfield_desde").fadeIn( tiempo, function() {});
            $("#errorfield_hasta").fadeIn( tiempo, function() {});
            $("#field_desde").val("");
            $("#field_hasta").val("") ;         
        }
        return regresar;
    }   

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::::::::::: Campos requeridos ::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/    
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