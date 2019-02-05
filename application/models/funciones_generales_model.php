<?php 
class Funciones_generales_model extends CI_Model { 

    public function __construct(){
        parent::__construct();
        $this->ci = get_instance();
        $this->ci->load->config('tank_auth', TRUE);
    }

    /*******************************************************/
    /******* MODELO PARA CRUD Y FUNCIONES GENERALES *******/
    /******************************************************/

    //Select query
    public function get_table_data($tabla, $where = null){
        if(!$tabla && !is_string($tabla)){
            return null;
        }
        $query = $this->db->select()->from($tabla);        
        if($where != null){
            $this->db->where($where);
        }

        return $query->get()->result_array();
    }
    
    //Save query
    public function save_data($tabla, $data, $tipo = false){         
        if ($tipo) { 
            $this->db->insert_batch($tabla, $data);
            return $this->db->affected_rows(); 
        } else {
            $this->db->insert($tabla, $data);           
            return $this->db->insert_id(); 
        }
    }

    //Update query
    public function udpate_data($tabla, $data, $where = null) {   
        if ($where != null) {
                $this->db->where($where);
        }            
        $this->db->update($tabla, $data);        
        return $this->db->affected_rows();
    } 

    //Delete query
    public function delete_data($tabla, $where) {
        $this->db->where($where);
        $this->db->delete($tabla);
        return $this->db->affected_rows();
    }

    //Encriptar password
    public function encriptar_data($data){
        $hasher = new PasswordHash(
                $this->ci->config->item('phpass_hash_strength', 'tank_auth'),
                $this->ci->config->item('phpass_hash_portable', 'tank_auth')
            );
        $hashed_password = $hasher->HashPassword($data);
        return $hashed_password;
    }
} 
?>