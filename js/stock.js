$(document).ready(function () { 
    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::: Cargar selects al iniciar :::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/    
    cargar_categorias(); 
    cargar_productos(); 

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
        var field_categoria = $("#field_categoria").val();
        var field_producto = $("#field_producto").val();       
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: url + 'consultas/reporte_stock',
            data: {'field_categoria': field_categoria,'field_producto':field_producto},            
            success: function (result) {
                $('#resultado').fadeIn( "slow", function() {});
                var tabla = ""; 
                if (result.length==0) {
                    tabla += '<tr>'+
                    '<td colspan = "3" align="center">Sin movimientos</td>'+ 
                    '</tr>';
                }                         
                for (var x in result) {
                    tabla += '<tr>'+
                    '<td>'+result[x].pr_codigo+'</td>'+
                    '<td>'+result[x].pr_nombre+'</td>'+
                    '<td>'+result[x].sa_cantidad+'</td>'+
                    '</tr>';                  
                }
                //console.log(tabla);                
                $("#reporte").html(tabla);
                $("#html").val(tabla);
            }
        }); 
    }) 

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::::::::: Generar PDF :::::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/ 
    $('#btn_pdf').click(function(){
        $("#data").submit();
    })
});