<form style="width: 100%;" id="frm_usuario" method="post" class="form-horizontal" autocomplete="off">  
    <input type="hidden" name="field_button" id="field_button">  
    <div class="col-md-12">      
        <div class="card-header">
            <span class="glyphicon glyphicon-plus-sign"></span> 
            <strong class="card-title pl-2">Añadir <?=$titulo?></strong>
        </div>
        <div class="tile" style="padding: 35px;">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">
                            Usuario<span class="required" style="color:red;"> *</span>  :
                        </label>
                        <div class="col-sm-8">
                            <input id="field_username" class="form-control" name="field_username" maxlength="49">
                            <label id="errorfield_username" class="error" style="display:none;"></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">
                            Rol<span class="required" style="color:red;"> *</span>  :
                        </label>
                        <div class="col-sm-8">
                            <select name="field_rol" id="field_rol" class="form-control" data-placeholder="Seleccione">
                                <option value="0">Administrador</option>
                                <option value="1">Empleado</option>
                            </select>
                            <label id="errorfield_rol" class="error" style="display:none;"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">
                            Contraseña<span class="required" style="color:red;"> *</span>  :
                        </label>
                        <div class="col-sm-8">
                            <input id="field_password" class="form-control" name="field_password" type="password" placeholder="Contraseña" maxlength="50">
                            <label id="errorfield_password" class="error" style="display:none;"></label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">
                            Correo electrónico :
                        </label>
                        <div class="col-sm-8">
                            <input id="field_correo" class="form-control" name="field_correo" maxlength="80">
                            <label id="errorfield_correo" class="error" style="display:none;"></label>
                        </div>
                    </div>
                </div>                    
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">
                            Confirmar<span class="required" style="color:red;"> *</span>  :
                        </label>
                        <div class="col-sm-8">
                            <input id="field_cPassword" class="form-control" name="field_cPassword" type="password" placeholder="Confirmar contraseña" maxlength="50">
                            <label id="errorfield_cPassword" class="error" style="display:none;"></label>
                        </div>
                    </div>
                </div>                    
            </div>
            <div class="row tile-footer" style="text-align: center;">                
                <div class="col-sm-12">
                    <div class="form-group">
                        <button class="btn btn-secondary" type="button" id="btn_guardar"><span class="glyphicon glyphicon-ok-sign"></span> Guardar</button> 
                        <button class="btn btn-success" type="button" id="btn_guardar_list"><span class="glyphicon glyphicon-saved"></span> Guardar y volver a la lista</button> 
                        <button class="btn btn-danger" type="button" id="btn_cancelar"><span class="glyphicon glyphicon-remove"></span> Cancelar</button> 
                    </div>
                </div> 
            </div>
        </div>
    </div>
</form>
<script type="text/javascript" src="<?= base_url() ?>js/agregarusuario.js"></script>