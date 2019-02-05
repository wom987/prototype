<?php 
class Consultas_model extends CI_Model {    

    /***************************************/
    /******* MODELO PARA REPORTERÍA *******/
    /***************************************/

    //Reporte de entradas
    function get_reporte_entradas($where = null) {        
        $this->db->select('pr_proveedores.pr_nombre AS proveedor,
            op_factura,
            pr_productos.pr_nombre AS producto,
            pr_productos.pr_codigo AS codigo,
            do_cantidad,
            do_precio,
            do_total');
        $this->db->from('op_operaciones');
        $this->db->join('pr_proveedores','pr_proveedores.pr_id = op_proveedor ','left');
        $this->db->join('do_detalle_operaciones','do_operaciones = op_id','left');
        $this->db->join('pr_productos','pr_productos.pr_id = do_producto','left');  
        $this->db->join('ca_categorias','ca_id = pr_productos.pr_categoria','left');         
        if ($where != null) {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result_array();
    } 

    //Reporte de salidas
    function get_reporte_salidas($where = null) {        
        $this->db->select('pr_productos.pr_nombre AS producto,
            pr_productos.pr_codigo AS codigo,
            do_cantidad,
            do_descripcion,
            de_nombre,
            do_recibe,
            do_descripcion,           
            DATE_FORMAT(op_fecha_modificacion, "%d/%m/%Y") as fecha');
        $this->db->from('op_operaciones');
        $this->db->join('do_detalle_operaciones','do_operaciones = op_id','left');
        $this->db->join('pr_productos','pr_productos.pr_id = do_producto','left');  
        $this->db->join('ca_categorias','ca_id = pr_productos.pr_categoria','left');           
        $this->db->join('de_departamentos','de_id= do_departamento','left');
        if ($where != null) {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result_array();
    } 

    //Reporte de stock
    function get_reporte_stock($where = null) {        
        $this->db->select('pr_codigo,
            pr_nombre,
            sa_cantidad');
        $this->db->from('sa_saldo');
        $this->db->join('pr_productos','pr_id = sa_producto','left'); 
        if ($where != null) {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result_array();
    } 

    //Reporte de instalaciones
    function get_reporte_instalaciones($where = null) {        
        $this->db->select('de_nombre,
            pr_codigo,
            pr_nombre,
            do_cantidad,
            do_descripcion,
            do_recibe,    
            DATE_FORMAT(op_fecha_modificacion, "%d/%m/%Y") as fecha');
        $this->db->from('op_operaciones');
        $this->db->join('do_detalle_operaciones','do_operaciones = op_id','left');
        $this->db->join('pr_productos','pr_productos.pr_id = do_producto','left');  
        $this->db->join('de_departamentos','de_id= do_departamento','left'); 
        if ($where != null) {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
}
?>