<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Operaciones extends CI_Controller {

    public function __construct()
    {
        parent::__construct(); 
        $this->load->model('operaciones_model'); 
        $this->load->model('consultas_model');
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        }                       
    }     

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::: Proceso de entradas :::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    function grid_entradas(){
        $data['registros'] = $this->operaciones_model->get_data_grid(array('op_tipo'=>1));
        $data['titulo'] = 'Entradas a inventario';
        $this->_cargarView('operaciones/gridentradas', $data);  
    }

    function entradas(){  
        if ($_POST) {
            $id_operacion = $this->save_generales($this->input->post());
            $datos = $this->save_detalles(array('do_operacion'=>$id_operacion, 'post'=>$this->input->post()));     
            $this->save_saldos(array('data'=>$datos, 'post'=>$this->input->post()));            
            $this->session->set_flashdata('msg', 'Los productos han sido ingresados al inventario.');
            redirect('operaciones/entradas');    
        }else{
            $data['titulo'] = 'Entradas a inventario';
            $this->_cargarView('operaciones/entradas', $data);  
        }
    } 

    // Guardando datos en tabla operaciones //
    function save_generales($generales){    
        $fecha = trim($generales['field_fecha']);
        $factura = trim($generales['field_factura']);
        $proveedor = trim($generales['field_proveedor']); 
        $total = trim($generales['cantidad_total']);

        $fecha = str_replace('/' ,'-' ,$fecha);        
        $date = new DateTime($fecha);

        $datos = array(
            'op_fecha_modificacion' => $date->format('Y-m-d H:i:s'),
            'op_factura' => $factura,
            'op_total' => $total,   
            'op_tipo' => 1,
            'op_proveedor' => $proveedor            
        );        
        $id = $this->funciones_generales_model->save_data('op_operaciones', $datos);
        return $id;     
    }   

    // Guardando datos en tabla do_detalle_operaciones //
    function save_detalles($detalles){ 
        $id_operacion = $detalles['do_operacion'];
        $cantidad = $detalles['post']['field_cantidad'];
        $precio = $detalles['post']['field_precio'];
        $total = $detalles['post']['field_total'];
        $producto = $detalles['post']['producto'];         
        $datos = array(); 
        foreach ($producto as $key => $value) { 
            if ($producto[$key]!='') {
                $datos[] = array(
                    'do_operaciones' => $id_operacion,
                    'do_cantidad' => $cantidad[$key],   
                    'do_precio' => $precio[$key],
                    'do_total' => $total[$key],
                    'do_producto' => $producto[$key]             
                ); 
            }                   
        } 
        //print_r($datos).die();  
        $this->funciones_generales_model->save_data('do_detalle_operaciones', $datos, true);
        return $datos;
    }

    // Guardando datos en tabla sa_saldo //
    function save_saldos($saldos){
        $fecha = trim($saldos['post']['field_fecha']);        
        $fecha = str_replace('/' ,'-' ,$fecha);  

        $date = new DateTime($fecha); 

        foreach ($saldos['data'] as $key => $value) { 
            $saldo = $this->funciones_generales_model->get_table_data('sa_saldo',array('sa_producto'=>$value['do_producto'])); 
            foreach ($saldo as $key => $valor) {
                $cantidad = $valor['sa_cantidad'];
                $cantidad += $value['do_cantidad'];                
                $this->funciones_generales_model->udpate_data('sa_saldo', array('sa_cantidad'=>$cantidad,'sa_fecha_modificacion'=>$date->format('Y-m-d H:i:s')), array('sa_producto ='=>$value['do_producto'])); 
            }                             
        } 
    } 

    // Eliminar entradas
    function eliminar_entradas(){
        $op_id = $this->input->post('id');        
        $respuesta = 0;       
        $detalle = 0; //Contador para determinar si quedará detalle de la operacion para no eliminarla
        $saldos = $this->funciones_generales_model->get_table_data('do_detalle_operaciones',array('do_operaciones' => $op_id));        
        foreach ($saldos as $key => $value) {
            $saldo = $this->funciones_generales_model->get_table_data('sa_saldo',array('sa_producto'=>$value['do_producto']));
            foreach ($saldo as $key => $valor) {
                $cantidad = $valor['sa_cantidad'];               
                //Se condiciona que las existencias del producto sean mayores al detalle de entrada que se desea eliminar
                if ($cantidad >= $value['do_cantidad']) {
                    $cantidad -= $value['do_cantidad'];
                    $this->funciones_generales_model->delete_data('do_detalle_operaciones',array('do_operaciones' => $op_id,'do_producto'=>$valor['sa_producto'])); 
                    $detalle++;                                                            
                } else{
                    $cantidad -= $valor['sa_cantidad'];
                    $cantidad_entrada = $value['do_cantidad']-$valor['sa_cantidad'];                    
                    $total_producto=  $cantidad_entrada * $value['do_precio'];
                    $total_entrada = $total_producto;                    
                    $this->funciones_generales_model->udpate_data('do_detalle_operaciones', array('do_cantidad'=>$cantidad_entrada,'do_total'=>$total_producto), array('do_producto ='=>$valor['sa_producto'],'do_operaciones ='=>$op_id)); 
                    $this->funciones_generales_model->udpate_data('op_operaciones', array('op_total'=>$total_entrada), array('op_id ='=>$op_id));
                    $respuesta = 1;
                    // $detalle = 1; 
                }                          
                $this->funciones_generales_model->udpate_data('sa_saldo', array('sa_cantidad'=>$cantidad), array('sa_producto ='=>$value['do_producto'])); 
            }  
        } 
        $nose = array();
        if (count($saldos)==$detalle) {
            $this->funciones_generales_model->delete_data('op_operaciones',array('op_id' => $op_id));                     
        } else{
            $nose = $this->funciones_generales_model->get_table_data('op_operaciones', array('op_id'=>$op_id));
        }
        echo json_encode(array('respuesta'=>$respuesta, 'resultado'=> ((count($nose)> 0)?$nose[0]['op_total']:'') ) );       
    }   

    function ver_entrada(){
        $id = $this->input->post('id');        
        $registros = $this->consultas_model->get_reporte_entradas(array('do_operaciones ='=>$id));
        echo json_encode($registros);
    }  

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::: Proceso de salidas ::::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    function grid_salidas(){
        $data['registros'] = $this->operaciones_model->get_data_grid(array('op_tipo'=>0));
        $data['titulo'] = 'Salidas de inventario';
        $this->_cargarView('operaciones/gridsalidas',$data);
    }

    function salidas(){
        if ($_POST) {
            $id_operacion = $this->save_generales_salidas($this->input->post());
            $datos = $this->save_detalles_salidas(array('do_operacion'=>$id_operacion, 'post'=>$this->input->post()));
            $this->modificar_saldos(array('data'=>$datos, 'post'=>$this->input->post()));
            $this->session->set_flashdata('msg', 'Los productos han sido descargados del inventario.');
            redirect('operaciones/salidas');
        }else{
            $data['titulo'] = 'Salidas de inventario';
            $this->_cargarView('operaciones/salidas', $data);
        }
    }    

    // Guardando datos en tabla operaciones con descarga //
    function save_generales_salidas($generales){        
        $fecha = trim($generales['field_fecha']); 
        $fecha = str_replace('/' ,'-' ,$fecha);        
        $date = new DateTime($fecha);
        $datos = array(
            'op_fecha_modificacion' => $date->format('Y-m-d H:i:s'),              
            'op_tipo' => 0                        
        );        
        $id = $this->funciones_generales_model->save_data('op_operaciones', $datos);
        return $id;
    }

    // Guardando datos en tabla do_operaciones con descarga(
    function save_detalles_salidas($detalles){
        $id_operacion = $detalles['do_operacion'];
        $cantidad = $detalles['post']['cantidad'];
        $departamento = $detalles['post']['departamento'];
        $recibe = $detalles['post']['recibe'];
        $descripcion = $detalles['post']['justificacion'];
        $producto = $detalles['post']['producto'];
        $datos = array();       
        foreach ($producto as $key => $value) {
            if ($producto[$key]!='') {
                $datos[] = array(
                    'do_operaciones' => $id_operacion,
                    'do_cantidad' => $cantidad[$key],
                    'do_departamento' => $departamento[$key],
                    'do_recibe' => $recibe[$key],
                    'do_descripcion' => $descripcion[$key],
                    'do_producto' => $producto[$key]
                );
            }
        }
        $this->funciones_generales_model->save_data('do_detalle_operaciones', $datos, true);
        return $datos;
    }

    function modificar_saldos($saldos){
        $fecha = trim(getdate('dd/mm/yyyy'));
        $fecha = str_replace('/' ,'-' ,$fecha);
        $date = new DateTime($fecha);
        foreach ($saldos['data'] as $key => $value) {
            $saldo = $this->funciones_generales_model->get_table_data('sa_saldo',array('sa_producto'=>$value['do_producto']));
            foreach ($saldo as $key => $valor) {
                $cantidad = $valor['sa_cantidad'];               
                $cantidad -= $value['do_cantidad'];
                $this->funciones_generales_model->udpate_data('sa_saldo', array('sa_cantidad'=>$cantidad,'sa_fecha_modificacion'=>$date->format('Y-m-d H:i:s')), array('sa_producto ='=>$value['do_producto']));
            }
        }
    }

    // Eliminar salidas
    function eliminar_salidas(){
        $op_id = $this->input->post('id');
        $saldos = $this->funciones_generales_model->get_table_data('do_detalle_operaciones',array('do_operaciones' => $op_id));
        foreach ($saldos as $key => $value) {
            $saldo = $this->funciones_generales_model->get_table_data('sa_saldo',array('sa_producto'=>$value['do_producto']));
            foreach ($saldo as $key => $valor) {
                $cantidad = $valor['sa_cantidad'];
                $cantidad += $value['do_cantidad'];                
                $this->funciones_generales_model->udpate_data('sa_saldo', array('sa_cantidad'=>$cantidad), array('sa_producto ='=>$value['do_producto'])); 
            }  
        }
        $this->funciones_generales_model->delete_data('do_detalle_operaciones',array('do_operaciones' => $op_id));
        $this->funciones_generales_model->delete_data('op_operaciones',array('op_id' => $op_id)); 
        redirect('operaciones/grid_salidas');         
    } 

    function ver_salida(){
        $id = $this->input->post('id');
        $registros = $this->consultas_model->get_reporte_salidas(array('do_operaciones ='=>$id));
        echo json_encode($registros);
    }

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::::: Select iniciales ::::::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    function proveedores(){
        $select_proveedores = $this->funciones_generales_model->get_table_data('pr_proveedores');
        $select = '<option value=""></option>';
        foreach ($select_proveedores as $key => $value) {
            $select .= '<option value="'.$value['pr_id'].'">'.$value['pr_nombre'].'</option>';
        }
        echo $select;
    }
    
    function productos(){        
        $buscar = $this->input->post('buscar');
        $this->db->like('pr_nombre', $buscar, 'both');
        $producto =  $this->funciones_generales_model->get_table_data('pr_productos');
        $resultados = array();
        foreach ($producto as $key => $value) {
            $resultados[] = array('id' => $value['pr_id'],'nombre' => $value['pr_nombre']);
        }
        echo json_encode($resultados);    
    }

    function departamentos(){
        $select_departamentos = $this->funciones_generales_model->get_table_data('de_departamentos');
        $select = '<option value=""></option>';
        foreach ($select_departamentos as $key => $value) {
            $select .= '<option value="'.$value['de_id'].'">'.$value['de_nombre'].'</option>';
        }
        echo $select;
    }

    function saldo(){
        $id = $this->input->post('id');
        $saldo = $this->funciones_generales_model->get_table_data('sa_saldo', array('sa_producto ='=>$id)); 
        foreach ($saldo as $key => $value) {
            $saldo = array('saldo' => $value['sa_cantidad']);
        }
        echo json_encode($saldo);
    }

    function productos_con_saldo(){        
        $buscar = $this->input->post('buscar');
        $this->db->like('pr_nombre', $buscar, 'both');
        $producto = $this->operaciones_model->get_producto_con_saldo(array('sa_cantidad >'=>0));
        $resultados = array();
        foreach ($producto as $key => $value) {
            $resultados[] = array('id' => $value['pr_id'],'nombre' => $value['pr_nombre']);
        }
        echo json_encode($resultados);    
    }

    public function _cargarView($vista, $datos = array())
    {           
        $data['vista'] = $vista;
        $data['datos'] = $datos;        
        $this->load->view('template', $data);   
    }   
}