<?php  
class Operaciones_model extends CI_Model {    

    /***************************************/
    /******* MODELO PARA OPERACIONES *******/
    /***************************************/

    //Grid de entradas y salidas
    function get_data_grid($where = null) {        
        $this->db->select('pr_nombre,
            op_id,
            op_factura,
            op_proveedor,
            op_fecha_modificacion,
            DATE_FORMAT(op_fecha_modificacion, "%d/%m/%Y") as fecha,
            op_total,
            SUM(do_cantidad) as cantidad');
        $this->db->from('op_operaciones');
        $this->db->join('pr_proveedores','pr_proveedores.pr_id = op_proveedor ','left');  
        $this->db->join('do_detalle_operaciones','do_operaciones = op_id ','left'); 
        $this->db->group_by('op_id');               
        if ($where != null) {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    //Productos con existencias
    function get_producto_con_saldo($where = null) {        
        $this->db->select('pr_nombre,
            pr_id,
            sa_cantidad');
        $this->db->from('pr_productos');
        $this->db->join('sa_saldo','sa_producto = pr_id ','left');         
        if ($where != null) {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
} 
?>