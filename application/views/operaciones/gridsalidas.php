<script type="text/javascript">
    $(document).ready(function(){
        $('#crear_salida').click(function(){
            $(location).attr('href', "salidas");
        })
    });
</script>
<div class="tile">
    <div class="row-fluid">
        <div class="box">
            <div class="box-body">
                <div class="table-responsive" style="overflow: auto; padding-top: 2px; padding-left: 2px; padding-right: 2px;display: block;">
                    <button type="button" class="btn btn-primary btn-block" id="crear_salida">
                        <span class="glyphicon glyphicon-plus"></span> Crear salida
                    </button>
                    <div style="height:10px;"></div>
                    <div class="content">
                        <table id="tabla" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha de instalación</th>
                                <th>Repuestos instalados</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if($registros){
                                $numero_fila = 1;
                                foreach ( $registros as $salidas ) { ?>
                                    <tr>
                                        <td><?=$numero_fila++?></td>
                                        <td><?=$salidas['fecha']?></td>
                                        <td><?=$salidas['cantidad']?></td>
                                        <td class="actions">
                                            <div class="bs-component">
                                                <ul class="nav nav-pills">
                                                    <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" style="background-color: #dc3545;color: white;">Acciones</a>
                                                        <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -122px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <?php if ($this->tank_auth->get_rol()==0) { ?>                                            
                                                            <a class="dropdown-item edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="anular" data-value="<?= $salidas['op_id']?>" role="button">
                                                                <span class="glyphicon glyphicon-trash"></span>
                                                                <span class="ui-button-text">Anular</span>
                                                            </a>
                                                            <a class="dropdown-item edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="ver" data-value="<?= $salidas['op_id']?>" role="button">
                                                                <span class="glyphicon glyphicon-eye-open"></span>
                                                                <span class="ui-button-text">Ver</span>
                                                            </a>                                                        
                                            <?php } else{ ?>
                                                            <a class="dropdown-item edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" id="ver" data-value="<?= $salidas['op_id']?>" role="button">
                                                                <span class="glyphicon glyphicon-eye-open"></span>
                                                                <span class="ui-button-text">Ver</span>
                                                            </a>
                                            <?php  } ?>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
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
            var tr = $(this).closest('tr');
            console.log(tr);
            swal({
                title: "¿Desea eliminar el registro?",
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
                        url: url +'operaciones/eliminar_salidas',
                        type: "POST",
                        dataType: "html",
                        data: { id: id },
                        success: function () {
                            swal("Eliminado", "El registro ha sido eliminado.", "success");
                            $('#tabla').dataTable().fnDestroy();
                            tr.remove();
                            $('#tabla').dataTable({"pageLength": 10});
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
        $('body').on('click', '#ver', function(){
            var id = $(this).data('value');
            $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: url + 'operaciones/ver_salida',
                    data: {'id': id},            
                    success: function (result) {                        
                        var tabla = "<div style='overflow-y:auto;width:auto;height:162px;'><table class='table table-bordered table-hover'>"; 
                        tabla += '<thead class="thead-dark"><tr>'+
                            '<th><center>Repuesto</center></th>'+ 
                            '<th><center>Cantidad</center></th>'+
                            '<th><center>Departamento</center></th>'+
                            '<th><center>Recibe</center></th>'+
                            '<th><center>Justificación</center></th>'+
                            '</tr></thead';                                                
                        for (var x in result) {
                            tabla += '<tbody><tr>'+
                            '<td>'+result[x].producto+'</td>'+ 
                            '<td>'+result[x].do_cantidad+'</td>'+
                            '<td>'+result[x].de_nombre+'</td>'+
                            '<td>'+result[x].do_recibe+'</td>'+                            
                            '<td>'+result[x].do_descripcion+'</td>'+                            
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