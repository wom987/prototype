<form action="<?php echo base_url('operaciones/salidas')?>" id="frm_salida" method="post" class="form-horizontal" autocomplete="off">
    <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <i class="fa fa-file-pdf-o"></i>
        <strong class="card-title pl-2">Información general</strong>
      </div> 
      <div class="card-body" style="padding: 35px;">
        <div class="row">
          <div class="col col-md-3">
            <label for="text-input" class=" form-control-label">Fecha</label>
            <span style='color:red' class='required'>*</span>
          </div>
          <div class="col-12 col-md-3"> 
            <input id="field_fecha" name="field_fecha" class="form-control date" required>
            <label id="errorfield_fecha" class="error" style="display:none;"></label>
          </div>                                                                               
        </div><br>        
      </div>
    </div><br>
  </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-file-pdf-o"></i>
                <strong class="card-title pl-2">Productos</strong>
            </div>
            <div class="card-body" style="padding: 35px;">
                <div class="row">
                    <div class="col col-md-12">
                        <table class="table table-hover table-bordered">
                            <thead class="table-danger">
                                <tr>
                                    <th scope="col" colspan="4">Detalles del producto a descargar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4">
                                        <table class="producto_detalle" style="width: 100%">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th scope="col">Nombre del repuesto</th>
                                                    <th scope="col">Departamento</th>
                                                    <th scope="col">Recibe</th>
                                                    <th scope="col">Justificación</th>
                                                    <th scope="col">Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="detalle">
                                                    <td style="width:30% !important;"><select name="field_producto" id="field_producto" class="form-control select_producto" data-placeholder="Seleccione" style="width:100% !important;"><option value=""></option></select><input type="hidden" name="producto[]" class="producto"></td>
                                                    <td style="width:20% !important;"><select name="field_departamento" id="field_departamento" class="form-control select_departamento" data-placeholder="Seleccione" style="width:100% !important;"><option value=""></option></select><input type="hidden" name="departamento[]" class="departamento"></td>
                                                    <td><input name="recibe[]" class="form-control recibe"></td>
                                                    <td><textarea name="justificacion[]" class="form-control justificacion"></textarea></td>
                                                    <td style="width:12%"><input name="cantidad[]" disabled="true" class="form-control cantidad"><input type="hidden" class="form-control saldo"><label id="errorfield_cantidad" class="error" style="display:none;"></label></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col col-md-12">
                        <center>
                            <button class="btn btn-danger" type="button" id="btn_guardar"><span class="glyphicon glyphicon-ok-sign"></span> Descargar</button>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript" src="<?= base_url() ?>js/agregarsalida.js"></script>