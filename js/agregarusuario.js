$(document).ready(function () { 

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::::: Guardar formulario ::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/ 
    
    $('#btn_guardar').click(function(){        
        $('#field_button').val('1');
        save();
    });
    $('#btn_guardar_list').click(function(){        
        $('#field_button').val('2');
        save();
    });    

    function save(){
        if(requeridos()){            
            $("#btn_guardar").prop( "disabled", true ); 
            $("#btn_guardar_list").prop( "disabled", true ); 
            $("#frm_usuario").submit(); 
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: url + 'configuraciones/agregar_usuarios',  
                data: $("#frm_usuario").serialize(),                           
                success: function (result) { 
                    window.location.href = result.redireccionar;
                }
            });                      
        } 
    }  

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::::::::::: Botón de cancelar ::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    $('#btn_cancelar').click(function(){
        swal({
            title: "¿Esta seguro que quiere volver a la lista?",
            text: "Los datos que intenta añadir no se han guardado.",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: "Si",
            cancelButtonText: "No",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(isConfirm) {            
            if (isConfirm) {             
                window.location = base_url+'configuraciones/grid_usuarios';
            }
        });
        return false;
    });

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::::::::::::: Validar usuario ::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    $("#field_username").change(function(){
        var user = $(this).val();
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: url + 'configuraciones/usuario_existente',  
            data: {'user': user},                            
            success: function (result) {                    
                if (result==1) {                        
                    alerta('El usuario ya existe.','warning','fa fa-exclamation-circle');                    
                    $("#field_username").val('');
                } 
            }
        });        
    })    

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::::: Validar contraseña ::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    $("#field_password").change(function(){
        var password = $(this).val();
        var cPassword = $('#field_cPassword').val(); 
        if (password.length <7) {
            alerta('La contraseña debe poseer un mínimo de 7 caracteres.','warning','fa fa-exclamation-circle');
            $(this).val('');
        } else{
            comparar_password(password,cPassword);
        }        
    })

    $("#field_cPassword").change(function(){
        var password = $('#field_password').val();
        var cPassword = $(this).val(); 
        comparar_password(password,cPassword);
    })    

    function comparar_password(password, cPassword){        
        if (password!='' && cPassword!='') {
            if (cPassword!=password) {
                alerta('Las contraseñas no coinciden.','warning','fa fa-exclamation-circle');
                $('#field_password').val('');
                $('#field_cPassword').val('');
            }
        }        
    }

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::: Validar correo electrónico ::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    $("#field_correo").change(function(){
        isEmail( $("#field_correo").val());
        var correo = $(this).val();
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: url + 'configuraciones/correo_existente',
            data: {'correo': correo},
            success: function (result) {
                if (result==1) {
                    alerta('El correo electrónico ya está en uso.','warning','fa fa-exclamation-circle');                    
                    $("#field_correo").val('');
                }
            }
        });
    }) 
    
    function isEmail(mail){
        var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var regresar = true;
        if(mail!=''){
            if (regex.test(mail)==false) {
                alerta('El correo electrónico es inválido.','warning','fa fa-exclamation-circle');
                regresar = false;
            }  
        }        
        return regresar;
    }

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::::::::::::::: Campos requeridos ::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    var tiempo = 1000;
    function requeridos(){
        var regresar = true;             
        if ($("#field_username").val()=='') {
            $("#errorfield_username").html("Campo requerido");
            $("#field_username").css("border-color", "red");
            $("#errorfield_username").fadeIn( tiempo, function() {});
            regresar = false;
        }  
        if ($("#field_rol").val()==null) {
            $("#errorfield_rol").html("Campo requerido");
            $("#errorfield_rol").fadeIn( tiempo, function() {});
            regresar = false;
        } 
        if ($("#field_password").val()=='') {
            $("#errorfield_password").html("Campo requerido");
            $("#field_password").css("border-color", "red");
            $("#errorfield_password").fadeIn( tiempo, function() {});
            regresar = false;
        } 
        if ($("#field_cPassword").val()=='') {
            $("#errorfield_cPassword").html("Campo requerido");
            $("#field_cPassword").css("border-color", "red");
            $("#errorfield_cPassword").fadeIn( tiempo, function() {});
            regresar = false;
        }     
        if(!isEmail($("#field_correo").val())){
            regresar = false;
        }    
        return regresar;
    }    

    $("#field_username").change(function(){
        $("#errorfield_username").fadeOut( tiempo, function() {});
        $("#field_username").css("border-color", "");
    }); 
    $("#field_rol").change(function(){
        $("#errorfield_rol").fadeOut( tiempo, function() {});
    }); 
    $("#field_password").change(function(){
        $("#errorfield_password").fadeOut( tiempo, function() {});
        $("#field_password").css("border-color", "");
    }); 
    $("#field_cPassword").change(function(){
        $("#errorfield_cPassword").fadeOut( tiempo, function() {});
        $("#field_cPassword").css("border-color", "");
    }); 
});