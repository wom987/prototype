<?php  
class Configuraciones_model extends CI_Model {    

    /***************************************/
    /***** MODELO PARA Configuraciones *****/
    /***************************************/

    //Grid de control de accesos
    function get_data_grid($where = null) {        
        $this->db->select('ip_address, 
            id,  
            COUNT(ip_address) as total,             
            DATE_FORMAT(time, "%d/%m/%Y") as fecha');
        $this->db->from('login_attempts');
        $this->db->group_by('ip_address');                        
        if ($where != null) {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    //Grid de usuarios
    function get_data_grid_usuarios($where = null) {
        $this->db->select('id,
            username,
            rol,
            email,
            last_login as uFechaLog');
        $this->db->from('users');
        $this->db->order_by("id", "asc");
        if ($where != null) {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result_array();
    }   
} 
?>