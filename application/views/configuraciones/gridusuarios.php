<script type="text/javascript">
    $(document).ready(function(){
        $('#crear_usuario').click(function(){
            $(location).attr("href",'<?php echo base_url('configuraciones/usuarios')?>');
        })
    });
</script>
<div class="tile" style="width: auto; max-width: 100%;">
    <div class="row-fluid">
        <div class="box">
            <div class="box-body">
                <div class="table-responsive" style="overflow: auto; padding-top: 2px; padding-left: 2px; padding-right: 2px;display: block;">
                    <button type="button" class="btn btn-primary btn-block" id="crear_usuario">
                        <span class="glyphicon glyphicon-plus"></span> Crear usuario
                    </button>
                    <div style="height:10px;"></div>
                    <div class="content">
                        <table  id="tabla" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Última sesión</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($registros){
                                $numero_fila = 1;
                                foreach ( $registros as $usuarios ) { ?>
                                    <tr>
                                        <td><?=$numero_fila++?></td>
                                        <td><?=$usuarios['username']?></td>
                                        <td><?php if($usuarios['rol']==0){
                                            echo "Administrador";
                                            }else{
                                                echo "Empleado";
                                            } ?></td> 
                                        <td><?=$usuarios['uFechaLog']?></td>
                                        <td class="actions">
                                            <?php if ($this->tank_auth->get_rol()==0){?>
                                            <div class="bs-component">
                                                <ul class="nav nav-pills">
                                                    <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="background-color: #dc3545;color: white;">Acciones</a>
                                                        <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -122px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                            <?php if ($this->tank_auth->get_username()!=$usuarios['username']) { ?>
                                                                <a class="dropdown-item edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="anular" data-value="<?= $usuarios['id']?>" role="button">
                                                                    <span class="glyphicon glyphicon-trash"></span>
                                                                    <span class="ui-button-text"> &nbsp;Eliminar</span>
                                                                </a> 
                                                                <a class="dropdown-item edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" href="<?php  echo base_url('configuraciones/editar_usuarios/')?><?= $usuarios['id']?>" role="button">
                                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                                    <span class="ui-button-text"> &nbsp;Editar</span>
                                                                </a>
                                                                <a class="dropdown-item edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="ver" data-value="<?= $usuarios['id']?>" role="button">
                                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                                    <span class="ui-button-text">Ver</span>
                                                                </a>                                                           
                                                            <?php } else {?>
                                                                <a class="dropdown-item edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="editar" href="<?php  echo base_url('configuraciones/editar_usuarios/')?><?= $usuarios['id']?>" role="button">
                                                                    <span class="glyphicon glyphicon-pencil"></span>
                                                                    <span class="ui-button-text"> &nbsp;Editar</span>
                                                                </a>
                                                                <a class="dropdown-item edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="ver" data-value="<?= $usuarios['id']?>" role="button">
                                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                                    <span class="ui-button-text">Ver</span>
                                                                </a>
                                                             
                                                            <?php } ?>   
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <?php } else{?> 
                                                <a class="dropdown-item edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="ver" data-value="<?= $usuarios['id']?>" role="button">
                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                    <span class="ui-button-text">Ver</span>
                                                </a>
                                            <?php }?>                                            
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
        //Anular
        $('body').on('click', '#anular', function(){
            var id = $(this).data('value');
            var tr = $(this).closest('tr');
            //console.log(tr);
            swal({
                title: "¿Desea eliminar el usuario?",
                text: "El registro será eliminado por completo",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: url +'configuraciones/delete_usuarios',
                        type: "POST",
                        dataType: "html",
                        data: { id: id },
                        success: function () {
                            swal("Eliminado!", "El registro ha sido eliminado.", "success");
                            $('#tabla').dataTable().fnDestroy();
                            tr.remove();
                            $('#tabla').dataTable({"pageLength": 6});
                            dataTable();
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
        //Ver
        $('body').on('click', '#ver', function(){
            var id = $(this).data('value');
            $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: url + 'configuraciones/ver_usuario',
                    data: {'id': id},            
                    success: function (result) {                        
                        var tabla = "<div style='overflow-y:auto;width:auto;height:162px;'><table class='table table-bordered table-hover'>"; 
                        tabla += '<thead class="thead-dark">'+
                            '<tr>'+
                            '<th><center>Usuario</center></th>'+ 
                            '<th><center>Rol</center></th>'+
                            '<th><center>Correo electrónico</center></th>'+
                            '<th><center>Última sesión</center></th>'+
                            '</tr>'+
                            '</thead';                                                                           
                        for (var x in result) {                            
                            var rol = ( result[x].rol == 0 ) ? 'Administrador' : 'Empleado';
                            tabla += '<tbody><tr>'+
                            '<td>'+result[x].username+'</td>'+ 
                            '<td>'+  rol  +'</td>'+                            
                            '<td>'+result[x].email+'</td>'+
                            '<td>'+result[x].uFechaLog+'</td>'+                            
                            '</tr></tbody>';                   
                        }
                        tabla +='</table></div>';
                        console.log(tabla);
                        swal({
                            title: '',
                            text: tabla,
                            html: true,
                            timer: 12000,
                          });                  
                    }
                });                        
        });
    });    
</script>