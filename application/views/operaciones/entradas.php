<form action="<?php echo base_url('operaciones/entradas')?>" id="frm_entrada" method="post" class="form-horizontal" autocomplete="off">
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
        <div class="row">
          <div class="col col-md-3">
            <label for="text-input" class=" form-control-label">Factura</label>
            <span style='color:red' class='required'>*</span>
          </div>
          <div class="col-12 col-md-3"> 
            <input id="field_factura" name="field_factura" class="form-control" required>
            <label id="errorfield_factura" class="error" style="display:none;"></label>
          </div>
          <div class="col col-md-3">
            <label for="text-input" class=" form-control-label">Proveedor</label>
            <span style='color:red' class='required'>*</span>
          </div>
          <div class="col-12 col-md-3">
            <select name="field_proveedor" id="field_proveedor" class="form-control" data-placeholder="Seleccione">
              <option value=""></option>
            </select>
            <label id="errorfield_proveedor" class="error" style="display:none;"></label>                           
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
                  <th scope="col" colspan="4">Detalles del producto</th>
                </tr>
              </thead>
              <tbody>                 
                <tr>
                  <td colspan="4">                
                    <table class="producto_detalle" style="width: 100%">
                      <thead class="table-secondary">
                        <tr>
                          <th scope="col">Nombre</th>
                          <th scope="col">Cantidad</th>
                          <th scope="col">Precio ($)</th>
                          <th scope="col">Total ($)</th>
                        </tr>
                      </thead>
                      <tbody>   
                        <tr class="detalle">
                          <td style="width:35% !important;">
                            <select name="field_producto" id="field_producto" class="form-control select_producto" data-placeholder="Seleccione" style="width:100% !important;">
                              <option value=""></option>
                            </select><input type="hidden" name="producto[]" class="producto"></td>
                            <td><input name="field_cantidad[]" class="form-control cantidad_producto numero" maxlength="10"><label id="errorfield_cantidad" class="error" style="display:none;"></label></td>
                            <td><input name="field_precio[]" class="form-control precio_producto numero" maxlength="10"><label id="errorfield_precio" class="error" style="display:none;"></label></td>
                            <td><input name="field_total[]" class="form-control totalizar_producto" value="0.00" readonly></td>
                          </tr>
                        </tbody>
                      </table>
                    </td>                   
                  </tr>
                  <tr class="active">
                    <td colspan="3" style="text-align: right;padding: 15px;"><b>Total factura ($): </b></td>
                    <td><input name="cantidad_total" id="cantidad_total" class="form-control cantidad_total" value="0.00" readonly></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col col-md-12">          <center>           
              <button class="btn btn-danger" type="button" id="btn_guardar"><span class="glyphicon glyphicon-ok-sign"></span> Guardar</button>
            </center>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<script type="text/javascript" src="<?= base_url() ?>js/agregarentrada.js"></script>