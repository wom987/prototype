<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php 
foreach($crud->css_files as $file): ?>
       <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<div style='height:20px;'></div>  
<!--<div style="padding: 10px">-->
<?php echo $crud->output; ?>
<!--</div-->
<?php foreach($crud->js_files as $file): ?>
    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<script type="text/javascript">
$(document).ready(function ($) {
    //Mail
    function isEmail(mail) { 
        var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(mail) ? true : false; 
    }
    //Deshabilita botones
    function buttons(state){       	
        $("#form-button-save").prop( "disabled", state );
        $("#save-and-go-back-button").prop( "disabled", state );
    } 

    $("#field-pr_nit").mask('0000-000000-000-0');
    $("#field-pr_telefono").mask('0000-0000');

    function isValidNit(nit){
        var re = /-/g;
        var nit = nit.replace(re, '');
        if ( nit.length <14 ) {       		
            alerta("Complete el campo NIT","danger");       		
            buttons(true);
            return true;         		
        } else {
            buttons(false); 
            return false;	
        }
    }

    function isValidMail(mail){
        if (mail!="") {
            if(!isEmail(mail)){       			
                alerta("El correo electrónico es inválido.",'danger');  
                buttons(true); 
                return true;       		
            } else {
                buttons(false); 
                return false; 	
            }
        } else {
            buttons(false); 
            return false; 	
        }
    }

    //Validar NIT      	
    $("#field-pr_nit").change(function() {
        var e = isValidNit($(this).val());           	
    });
    //Validar email
    $("#field-pr_correo").change(function(){        	
        var d = isValidMail($(this).val());    
    }); 
});
</script>
<script type="text/javascript" src="<?=base_url()?>js/jquery.mask.js"></script>