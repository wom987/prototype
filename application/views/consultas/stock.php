<!-- Filtros de vista -->
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <i class="fa fa-file-pdf-o"></i>
            <strong class="card-title pl-2">Filtros de búsqueda</strong>
        </div>
        <div class="card-body" style="padding: 35px;">        
            <form action="" id="frm_search" name="frm_search" method="post" class="form-horizontal" autocomplete="off">
                <div class="row">
                    <div class="col col-md-3">
                        <label for="text-input" class=" form-control-label">Categoría</label>
                    </div>
                    <div class="col-12 col-md-3">
                        <select name="field_categoria" id="field_categoria" class="form-control cargar_producto" data-placeholder="Seleccione"> 
                            <option value=""></option>
                        </select>   
                    </div>
                    <div class="col col-md-3">
                        <label for="text-input" class=" form-control-label">Producto</label>
                    </div>
                    <div class="col-12 col-md-3">
                        <select name="field_producto" id="field_producto" class="form-control" data-placeholder="Seleccione"> 
                            <option value=""></option>
                        </select>   
                    </div>
                </div><br>
                <div class="tile-footer">
                    <center>
                        <button class="btn btn-secondary" type="button" id="btn_search">
                            <span class="glyphicon glyphicon-search"></span> Buscar
                        </button>
                    </center>               
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Resultados de búsqueda -->
<div class="col-md-12" id="resultado" style="display: none;"><br>
    <div class="card">
        <div class="card-header">
            <i class="fa fa-table"></i>
            <strong class="card-title pl-2">Reporte</strong>
        </div>
        <div style="padding: 20px;">         
            <table  class="table table-bordered">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Saldo actual</th>
                    </tr>
                </thead>
                <form action="<?php echo base_url('consultas/print_stock')?>" method="post" target="_blank" id="data">
                    <tbody id="reporte">
                        <!--Aquí se dibujará el contenido desde js-->
                        <textarea id="html" name="html" style="display: none;"></textarea>                    
                    </tbody>
                </form>
            </table> 
        </div>
        <div class="tile-footer" style="padding: 20px;">
            <center>
                <button class="btn btn-danger" type="submit" id="btn_pdf">
                    <span class="glyphicon glyphicon-open-file"></span> Generar PDF
                </button>&nbsp;&nbsp;&nbsp;
            </center>               
        </div>
    </div> 
</div>       
<script type="text/javascript" src="<?= base_url() ?>js/stock.js" ></script>