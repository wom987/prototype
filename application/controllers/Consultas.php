<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consultas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        }
        $this->load->model('consultas_model');
        $this->load->library('pdf');
        $this->firmas= '<br><br><br><br><br><br>
        <table align="center">
            <tr><td>F. _________________________</td><td>F. _________________________</td></tr>
            <tr><td align="center">Jennifer Ivonne Menjivar Villacorta</td><td align="center">Edwin Alexander Ulloa</td></tr>
            <tr><td align="center"><b>Técnico que supervisa</b></td><td align="center"><b>Jefe de informática</b></td></tr>
        </table>'; 
    } 

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::: Reporte de entradas :::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    function entradas(){                
        $data['titulo'] = 'Reporte de entradas';
        $this->_cargarView('consultas/entradas', $data);
    } 

    function reporte_entradas(){
        //Captura de datos del js
        $desde = $this->input->post('field_desde');        
        $hasta = $this->input->post('field_hasta');
        $categoria = $this->input->post('field_categoria');
        $producto = $this->input->post('field_producto'); 

        $inicio='DATE(op_fecha_modificacion)';  
        $fin = 'DATE(op_fecha_modificacion)';
        //Consulta de datos al modelo aplicando filtros de campos recibidos desde ajax
        if ($categoria=='' && $producto=='') {
            $registros = $this->consultas_model->get_reporte_entradas(array( $inicio.'>='=>$desde, $fin.'<='=>$hasta, 'op_tipo ='=>1));
        }else if($categoria==0 && $producto!='' or $categoria=='' && $producto!=''){
            $registros = $this->consultas_model->get_reporte_entradas(array($inicio.'>='=>$desde, $fin.'<='=>$hasta, 'pr_productos.pr_id ='=> $producto, 'op_tipo ='=>1));
        }else if($categoria!=0 && $producto==''){
            $registros = $this->consultas_model->get_reporte_entradas(array($inicio.'>='=>$desde, $fin.'<='=>$hasta, 'ca_id ='=> $categoria, 'op_tipo ='=>1));
        }else if($categoria!=0 && $producto!=''){
            $registros = $this->consultas_model->get_reporte_entradas(array($inicio.'>='=>$desde, $fin.'<='=>$hasta, 'ca_id ='=> $categoria,'pr_productos.pr_id ='=> $producto, 'op_tipo ='=>1));
        }
        else if ($categoria==0 && $producto=='') {
            $registros = $this->consultas_model->get_reporte_entradas(array($inicio.'>='=>$desde, $fin.'<='=>$hasta, 'op_tipo ='=>1, 'ca_id='=>null));
        }  

        //Ordenamiento de array
        $arreglo = [];   
        foreach ($registros as $key => $value) {
            if(isset($arreglo[$value['op_factura']])){
                array_push($arreglo[$value['op_factura']]["detalles"], array("producto"=>$value['producto'], "cantidad"=>$value["do_cantidad"],"proveedor"=>$value['proveedor'],"codigo"=>$value['codigo'],"total"=>$value['do_total'],"precio"=>$value['do_precio'],"total"=>$value['do_total']));
            } else{
                $arreglo[$value['op_factura']] = array("nombre_factura"=>$value['op_factura'], "detalles"=>array(array("producto"=>$value['producto'], "cantidad"=>$value["do_cantidad"],"proveedor"=>$value['proveedor'],"codigo"=>$value['codigo'],"total"=>$value['do_total'],"precio"=>$value['do_precio'],"total"=>$value['do_total'])));
            }
        }
        
        //Creando tr con la data
        $tabla = "";
        foreach ($arreglo as $key => $value) {
            $tabla .= '<tr>';
            $tabla .= '<td colspan="6" style="background-color:#dfdfdf;color:black;text-align:center;font-size:12px;font-family:sans-serif;"> N° Factura: '.$value['nombre_factura'].'</td>';
            $tabla .= '</tr>';
            foreach ($value["detalles"] as $key2 => $value2) {
                $tabla .= '<tr>';
                $tabla .= '<td>'.$value2["codigo"].'</td>';
                $tabla .= '<td>'.$value2["producto"].'</td>';
                $tabla .= '<td>'.$value2["cantidad"].'</td>';
                $tabla .= '<td> $'.$value2["precio"].'</td>';
                $tabla .= '<td> $'.$value2["total"].'</td>';
                $tabla .= '<td>'.$value2["proveedor"].'</td>';
                $tabla .= '</tr>';
            }
        }
        //print_r($this->db->last_query()).die();
        //print_r($tabla).die();
        echo json_encode($tabla);       
    }

    function print_entradas(){
        $html = '<table  border="1" cellpadding="2">                
        <tr style="background-color:#0b409c;color:white;text-align: center;font-size:12px;font-family:sans-serif;">
        <th width="55px">Código</th>
        <th width="95px">Producto</th>
        <th>Cantidad</th>
        <th width="105px">Precio Unitario</th>
        <th>Total</th>
        <th>Proveedor</th>        
        </tr>';
        $html .= $this->input->post('html');
        $html .= '</table>';       
        $html .= $this->firmas;      
        //print_r($html).die();    
        $this->_loadPdf('Reporte de entradas', $html);       
    }

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::: Reporte de salidas ::::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
     function salidas(){                
        $data['titulo'] = 'Reporte de salidas';
        $this->_cargarView('consultas/salidas', $data);
    } 

    function reporte_salidas(){
        //Captura de datos del js
        $desde = $this->input->post('field_desde');
        $hasta = $this->input->post('field_hasta'); 
        $categoria = $this->input->post('field_categoria');
        $producto = $this->input->post('field_producto'); 

        $inicio='DATE(op_fecha_modificacion)';  
        $fin = 'DATE(op_fecha_modificacion)';
        //Consulta de datos al modelo aplicando filtros de campos recibidos desde ajax
        if ($categoria=='' && $producto=='') {
            $registros = $this->consultas_model->get_reporte_salidas(array( $inicio.'>='=>$desde, $fin.'<='=>$hasta, 'op_tipo ='=>0));
        }else if($categoria==0 && $producto!='' or $categoria=='' && $producto!=''){
            $registros = $this->consultas_model->get_reporte_salidas(array($inicio.'>='=>$desde, $fin.'<='=>$hasta, 'pr_productos.pr_id ='=> $producto, 'op_tipo ='=>0));
        }else if($categoria!=0 && $producto==''){
            $registros = $this->consultas_model->get_reporte_salidas(array($inicio.'>='=>$desde, $fin.'<='=>$hasta, 'ca_id ='=> $categoria, 'op_tipo ='=>1));
        }else if($categoria!=0 && $producto!=''){
            $registros = $this->consultas_model->get_reporte_salidas(array($inicio.'>='=>$desde, $fin.'<='=>$hasta, 'ca_id ='=> $categoria,'pr_productos.pr_id ='=> $producto, 'op_tipo ='=>0));
        }
        else if ($categoria==0 && $producto=='') {
            $registros = $this->consultas_model->get_reporte_salidas(array($inicio.'>='=>$desde, $fin.'<='=>$hasta, 'op_tipo ='=>0, 'ca_id='=>null));
        }     

        //Ordenamiento de array
        $arreglo = [];   
        foreach ($registros as $key => $value) {
            if(isset($arreglo[$value['producto']])){
                array_push($arreglo[$value['producto']]["detalles"], array("do_cantidad"=>$value['do_cantidad'], "fecha"=>$value["fecha"]));
            } else{
                $arreglo[$value['producto']] = array("producto"=>$value['producto'], "detalles"=>array(array("do_cantidad"=>$value['do_cantidad'], "fecha"=>$value["fecha"])));
            }
        }
        
        //Creando tr con la data
        $tabla = "";
        foreach ($arreglo as $key => $value) {
            $tabla .= '<tr>';
            $tabla .= '<td colspan="2" style="background-color:#dfdfdf;color:black;text-align:center;font-size:12px;font-family:sans-serif;"> Producto: '.$value['producto'].'</td>';
            $tabla .= '</tr>';
            foreach ($value["detalles"] as $key2 => $value2) {
                $tabla .= '<tr style="text-align: center;">';
                $tabla .= '<td>'.$value2["do_cantidad"].'</td>';
                $tabla .= '<td>'.$value2["fecha"].'</td>';
                $tabla .= '</tr>';
            }
        }
        //print_r($this->db->last_query()).die();
        //print_r($tabla).die();
        echo json_encode($tabla); 
    }

    function print_salidas(){
        $html = '<table  border="1" cellpadding="2" style="text-align: center;">                
        <tr style="background-color:#0b409c;color:white;text-align: center;font-size:12px;font-family:sans-serif;">       
        <th>Cantidad</th>        
        <th>Fecha de salida</th>
        </tr>';
        $html .= $this->input->post('html');
        $html .= '</table>';       
        $html .= $this->firmas;
        //print_r($html).die();    
        $this->_loadPdf('Reporte de salidas', $html);       
    }

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::: Reporte de stock :::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/

    function stock(){                
        $data['titulo'] = 'Reporte de Existencias';
        $this->_cargarView('consultas/stock', $data);
    } 

    function reporte_stock(){
        //Captura de datos del js
        $categoria = $this->input->post('field_categoria');
        $producto = $this->input->post('field_producto'); 

        //Consulta de datos al modelo aplicando filtros de campos recibidos desde ajax
        if ($categoria=='' && $producto=='') {
            $registros = $this->consultas_model->get_reporte_stock();
        }else if($categoria==0 && $producto!='' or $categoria=='' && $producto!=''){
            $registros = $this->consultas_model->get_reporte_stock(array('pr_id ='=> $producto));
        }else if($categoria!=0 && $producto==''){
            $registros = $this->consultas_model->get_reporte_stock(array('pr_categoria ='=> $categoria));
        }else if($categoria!=0 && $producto!=''){
            $registros = $this->consultas_model->get_reporte_stock(array('pr_categoria ='=> $categoria,'pr_id ='=> $producto));
        }
        else if ($categoria==0 && $producto=='') {
            $registros = $this->consultas_model->get_reporte_stock(array('pr_categoria='=>null));
        }                
        //print_r($registros).die();
        echo json_encode($registros);       
    }

    function print_stock(){
        $html = '<table  border="1" cellpadding="2">                
        <tr style="background-color:#0b409c;color:white;text-align: center;font-size:12px;font-family:sans-serif;">
        <th>Código</th>
        <th>Producto</th>
        <th>Saldo actual</th>
        </tr>';
        $html .= $this->input->post('html');
        $html .= '</table>';       
        $html .= $this->firmas;
        //print_r($html).die();    
        $this->_loadPdf('Reporte de existencias', $html);       
    }

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::::: Reporte de instalaciones :::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
     function instalaciones(){                
        $data['titulo'] = 'Reporte de Instalaciones';
        $this->_cargarView('consultas/instalaciones', $data);
    } 

    function reporte_instalaciones(){
        //Captura de datos del js
        $desde = $this->input->post('field_desde');
        $hasta = $this->input->post('field_hasta'); 
        $departamento = $this->input->post('field_departamento'); 

        $inicio='DATE(op_fecha_modificacion)';  
        $fin = 'DATE(op_fecha_modificacion)';
        //Consulta de datos al modelo aplicando filtros de campos recibidos desde ajax
        if ($departamento=='') {
            $registros = $this->consultas_model->get_reporte_instalaciones(array( $inicio.'>='=>$desde, $fin.'<='=>$hasta, 'op_tipo ='=>0));
        }  
        else {
            $registros = $this->consultas_model->get_reporte_instalaciones(array( $inicio.'>='=>$desde, $fin.'<='=>$hasta, 'op_tipo ='=>0,'do_departamento ='=>$departamento));
        }         
        //Ordenamiento de array
        $arreglo = [];   
        foreach ($registros as $key => $value) {
            if(isset($arreglo[$value['de_nombre']])){
                array_push($arreglo[$value['de_nombre']]["detalles"], array("producto"=>$value['pr_nombre'], "cantidad"=>$value["do_cantidad"],"descripcion"=>$value['do_descripcion'],"codigo"=>$value['pr_codigo'],"recibe"=>$value['do_recibe'],"fecha"=>$value['fecha']));
            } else{
                $arreglo[$value['de_nombre']] = array("departamento"=>$value['de_nombre'], "detalles"=>array(array("producto"=>$value['pr_nombre'], "cantidad"=>$value["do_cantidad"],"descripcion"=>$value['do_descripcion'],"codigo"=>$value['pr_codigo'],"recibe"=>$value['do_recibe'],"fecha"=>$value['fecha'])));
            }
        }
        
        //Creando tr con la data
        $tabla = "";
        foreach ($arreglo as $key => $value) {
            $tabla .= '<tr>';
            $tabla .= '<td colspan="6" style="background-color:#dfdfdf;color:black;text-align:center;font-size:12px;font-family:sans-serif;"> Departamento: '.$value['departamento'].'</td>';
            $tabla .= '</tr>';
            foreach ($value["detalles"] as $key2 => $value2) {
                $tabla .= '<tr>';
                $tabla .= '<td>'.$value2["codigo"].'</td>';
                $tabla .= '<td>'.$value2["producto"].'</td>';
                $tabla .= '<td>'.$value2["cantidad"].'</td>';
                $tabla .= '<td>'.$value2["descripcion"].'</td>';
                $tabla .= '<td>'.$value2["recibe"].'</td>';
                $tabla .= '<td>'.$value2["fecha"].'</td>';
                $tabla .= '</tr>';
            }
        }
        //print_r($this->db->last_query()).die();
        //print_r($tabla).die();
        echo json_encode($tabla);       
              
    }

    function print_instalaciones(){
        $html = '<table  border="1" cellpadding="2">                
        <tr style="background-color:#0b409c;color:white;text-align: center;font-size:12px;font-family:sans-serif;">        
        <th>Código</th>
        <th>Producto</th>
        <th>Instalaciones</th>
        <th>Justificación</th> 
        <th>Recibe</th>     
        <th>Fecha de instalación</th>
        </tr>';
        $html .= $this->input->post('html');
        $html .= '</table>';       
        $html .= $this->firmas;
        //print_r($html).die();    
        $this->_loadPdf('Reporte de instalaciones', $html);       
    }

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::: Filtros en reportes :::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
         
    function categorias(){
        $select_categorias = $this->funciones_generales_model->get_table_data('ca_categorias');
        $select = '<option value=""></option>';
        $select .= '<option value="0">Sin categoría</option>';
        foreach ($select_categorias as $key => $value) {
            $select .= '<option value="'.$value['ca_id'].'">'.$value['ca_nombre'].'</option>';
        }
        echo $select;
    }

    function todos_productos(){
        $select_productos = $this->funciones_generales_model->get_table_data('pr_productos');
        $select = '<option value=""></option>';
        foreach ($select_productos as $key => $value) {
            $select .= '<option value="'.$value['pr_id'].'">'.$value['pr_nombre'].'</option>';
        }
        echo $select;
    }

    function productos(){
        $categoria = $this->input->post('categoria');
        if ($categoria=='') {
            $select_productos = $this->funciones_generales_model->get_table_data('pr_productos');           
        }else if ($categoria==0) {
            $select_productos = $this->funciones_generales_model->get_table_data('pr_productos', array('pr_categoria ='=>null));
        }else{
            $select_productos = $this->funciones_generales_model->get_table_data('pr_productos', array('pr_categoria ='=>$categoria)); 
        }

        $select = '<option value=""></option>';
        foreach ($select_productos as $key => $value) {
            $select .= '<option value="'.$value['pr_id'].'">'.$value['pr_nombre'].'</option>';
        }
        echo $select;
    }   

    function departamentos(){
        $select_departamentos = $this->funciones_generales_model->get_table_data('de_departamentos');        
        $select .= '<option value=""></option>';
        foreach ($select_departamentos as $key => $value) {
            $select .= '<option value="'.$value['de_id'].'">'.$value['de_nombre'].'</option>';
        }
        echo $select;
    }   

    public function _cargarView($vista, $datos = array())
    {           
        $data['vista'] = $vista;
        $data['datos'] = $datos;        
        $this->load->view('template', $data);   
    } 

    public function _loadPdf($titulo, $contenido){
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);     
        $pdf->SetTitle($titulo);
        // Cabecera 
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $titulo, PDF_HEADER_STRING,array(27,38,49), array(52,152,219));
        //Pie
        $pdf->setFooterData(array(40,55,71), array(52,152,219 ));
        //Margen
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);   

        
        $pdf->AddPage();       
        $pdf->writeHTML($contenido, true, false, true, false, '');
        $pdf->lastPage();
        $pdf->Output($titulo.'.pdf', 'I');
    }  
}
?>