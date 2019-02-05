<script type="text/javascript">
    $(document).ready(function(){  
     $('body').on('click', '#eliminar_todo', function(){  
        $(location).attr('href', "eliminar_todo");        
     }); 
        
        $("#boton").html(eliminarBoton());
   });
    function eliminarBoton(){
        var boton="";        
        var rows = $('#tabla >tbody >tr').length; 
        var vacio = $('#tabla >tbody >tr >td[class="dataTables_empty"]').length;        
        if (vacio==1) {
            rows=0;
        }       
        
        if (rows>0) {
            <?php if ($this->tank_auth->get_rol()==0) { ?>
                boton = "<button type='button' class='btn btn-primary btn-block' id='eliminar_todo'><span class='glyphicon glyphicon-remove'></span> Eliminar todo</button>"; 
            <?php  } ?>
        } else {
            boton = "";
        }
        return  boton;
    } 
</script>
<div class="tile">    
    <div class="row-fluid">
        <div class="box"> 
            <div class="box-body">
                <div class="table-responsive" style="overflow: auto; padding-top: 2px; padding-left: 2px; padding-right: 2px;display: block;">
                    <div id="boton"></div>               
                    <div style="height:10px;"></div>                          
                    <div class="content">
                        <table id="tabla" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ip bloqueada</th>
                                    <th>Fecha de acceso</th>
                                    <th>Intentos fallidos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if($registros){
                                    $numero_fila = 1;
                                    foreach ( $registros as $intentos ) { ?>
                                        <tr>
                                            <td><?=$numero_fila++?></td>
                                            <td><input type="hidden" id="address" value="<?=$intentos['ip_address']?>"><?=$intentos['ip_address']?></td>
                                            <td><?=$intentos['fecha']?></td>  
                                            <td><?=$intentos['total']?></td>                                          
                                            <td class="actions">  
                                                <?php if ($this->tank_auth->get_rol()==0) {         
                                                    ?>                                                                 
                                                <div class="bs-component">
                                                    <ul class="nav nav-pills">
                                                        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="background-color: #dc3545;color: white;">Acciones</a>
                                                            <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -122px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                <a class="dropdown-item edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="anular" role="button">
                                                                    <span class="glyphicon glyphicon-trash"></span>
                                                                    <span class="ui-button-text">Eliminar</span>
                                                                </a>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div> 
                                                <?php 
                                                } else{
                                                    ?>
                                                    Sin acciones
                                                <?php 
                                                }?>
                                            </td>
                                        </tr>
                                    <?php } }  ?>
                                </tbody>        
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>     
    </div>  

<script type="text/javascript">
    $(document).ready(function(){
        $('#tabla').DataTable({
            "pageLength": 10              
        });
        $('body').on('click', '#anular', function(){        
            var id = $(this).data('value');
            var address = $("#address").val();
            //console.log(address);
            var tr = $(this).closest('tr');
            //console.log(tr);
            swal({
                title: "¿Desea eliminar el registro?",
                text: "El acceso será desbloqueado",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {            
                if (isConfirm) {             
                    $.ajax({
                        url: url +'configuraciones/eliminar_accesos',
                        type: "POST",                    
                        dataType: "html",
                        data: { address: address },
                        success: function () {                            
                            swal("Eliminado!", "El registro ha sido eliminado.", "success");
                            $('#tabla').dataTable().fnDestroy();
                            tr.remove();
                            $('#tabla').dataTable({"pageLength": 10});
                            dataTable();
                            $("#boton").html(eliminarBoton());
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            swal("Error!", "Intente de nuevo", "error");
                        }
                    });
                } else {
                    swal("Cancelado", "El registro no fue eliminado", "error");
                }
            });
        });
    });
</script>