<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configuraciones extends CI_Controller {

    public function __construct()
    {
        parent::__construct();               
        $this->load->model('configuraciones_model');
        $this->ci = get_instance();
        $this->ci->load->config('tank_auth', TRUE);        
    }     

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    :::::::::::::::::: Proceso de control de acceso :::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
    function grid_accesos(){
        if (!$this->tank_auth->is_logged_in() || $this->tank_auth->get_rol()!=0) {           
            redirect('/auth/login/');
        }
        //print_r($this->tank_auth->get_rol()).die();
        $data['registros'] = $this->configuraciones_model->get_data_grid();
        $data['titulo'] = 'Control de acceso';
        $this->_cargarView('configuraciones/gridaccesos', $data);      
    }
    

    // Eliminar accesos 
    function eliminar_accesos(){
        if (!$this->tank_auth->is_logged_in() || $this->tank_auth->get_rol()!=0) {           
            redirect('/auth/login/');
        }
        $address = $this->input->post('address');       
        $this->funciones_generales_model->delete_data('login_attempts',array('ip_address' => $address));
        redirect('configuraciones/grid_accesos');         
    } 

    // Eliminar todos los accesos 
    function eliminar_todo(){   
        if (!$this->tank_auth->is_logged_in() || $this->tank_auth->get_rol()!=0) {           
            redirect('/auth/login/');
        }    
        $this->funciones_generales_model->delete_data('login_attempts',array('id >' => 0));
        redirect('configuraciones/grid_accesos');         
    } 

    /*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::::: Proceso de usuarios :::::::::::::::::::::::
    :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/  

    function grid_usuarios(){
        if (!$this->tank_auth->is_logged_in() || $this->tank_auth->get_rol()!=0) {           
            redirect('/auth/login/');
        }
        $data['titulo'] = 'Usuarios';
        $data['registros'] = $this->configuraciones_model->get_data_grid_usuarios();
        $this->_cargarView('configuraciones/gridusuarios', $data);
    }  

    //Vista usuarios
    function usuarios(){
        if (!$this->tank_auth->is_logged_in() || $this->tank_auth->get_rol()!=0) {           
            redirect('/auth/login/');
        }
        $data['titulo']='Usuarios';
        $this->_cargarView('configuraciones/usuarios',$data);        
    }
    //Guardar usuarios
    function agregar_usuarios(){   
        if (!$this->tank_auth->is_logged_in() || $this->tank_auth->get_rol()!=0) {           
            redirect('/auth/login/');
        } 
        $button = $this->input->post('field_button');
        date_default_timezone_set("America/El_Salvador"); 

        $datos = array(
            'rol' => $this->input->post('field_rol'),
            'email' => $this->input->post("field_correo"),
            'username' => $this->input->post('field_username'),                   
            'password' => $this->funciones_generales_model->encriptar_data($this->input->post('field_password')),
            'activated' => 1,
            'last_ip' =>$this->input->ip_address(),
            'banned' => 0,
            'last_login' => '0000-00-00 00:00:00',
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s')
        );       
         
        $respuesta["redireccionar"] = ($button==1) ? base_url().'configuraciones/usuarios': base_url().'configuraciones/grid_usuarios';
        $this->funciones_generales_model->save_data('users', $datos);
        $this->session->set_flashdata('msg', 'El usuario ha sido creado.'); 
        echo json_encode($respuesta); 
    }    

    //Eliminar usuarios
    function delete_usuarios(){
        if (!$this->tank_auth->is_logged_in() || $this->tank_auth->get_rol()!=0) {           
            redirect('/auth/login/');
        }
        $op_id = $this->input->post('id');
        $this->funciones_generales_model->delete_data('users',array('id' => $op_id));
        redirect('configuraciones/grid_usuarios');
    } 

    //Ver usuarios
    function ver_usuario(){
        if (!$this->tank_auth->is_logged_in() || $this->tank_auth->get_rol()!=0) {           
            redirect('/auth/login/');
        }
        $id = $this->input->post('id');
        $registros = $this->configuraciones_model->get_data_grid_usuarios(array('id ='=>$id));
        echo json_encode($registros);
    } 

    //Vista editar usuarios
    function editar_usuarios($id){
        if (!$this->tank_auth->is_logged_in() || $this->tank_auth->get_rol()!=0) {           
            redirect('/auth/login/');
        }
        $data['id'] = $id;
        $data['titulo'] = 'Editar Usuario';
        $data['detalles'] = $this->funciones_generales_model->get_table_data('users', array('id' => $id));
        $this->_cargarView('configuraciones/editar_usuarios',$data);
    } 

    //Editar usuarios
    function editar(){
        $button = $this->input->post('field_button');
        $id = $this->input->post('field_id');
        $rol = $this->input->post('field_rol');
        $username = $this->input->post('field_username');   
        $estado = $this->input->post('field_estado'); 
        $password = $this->input->post('field_password');
        $message = '';       
        date_default_timezone_set("America/El_Salvador"); 
        //Si se modifica contraseña
        if($password!=''){
            $datos = array(
                'rol' => $rol,
                'email' => $this->input->post("field_correo"),
                'username' => $username,                   
                'password' => $this->funciones_generales_model->encriptar_data($password),
                'activated' => $estado,
                'modified' => date('Y-m-d H:i:s')
            );
        } else {
            $datos = array(
                'rol' => $rol,
                'email' => $this->input->post("field_correo"),
                'username' => $username,
                'activated' => $estado,
                'modified' => date('Y-m-d H:i:s')
            ); 
        }

        //Se actualiza la sesión si se está editando el usuario activo
        if($this->tank_auth->get_user_id() == $id ){ 
            $_SESSION['rol'] = $rol;
            $_SESSION['username'] = $username;
            $respuesta["redireccionar"] = ($button==1) ? base_url().'configuraciones/editar_usuarios/'.$id: base_url().'configuraciones/grid_usuarios';
            $message = 'El usuario ha sido actualizado.';  
            //Si se pasa a inactivo el usuario actual se cierra la sesión         
            if ($estado==0) {                               
                $respuesta["redireccionar"] = base_url().'auth/logout';
                $message = 'El usuario actual fue dado de baja.';                               
            } 
            $this->session->set_flashdata('msg', $message);
            $this->funciones_generales_model->udpate_data('users', $datos, array('id' => $id));
            echo json_encode($respuesta);           
        } else {
            $respuesta["redireccionar"] = ($button==1) ? base_url().'configuraciones/editar_usuarios/'.$id: base_url().'configuraciones/grid_usuarios'; 
            $this->funciones_generales_model->udpate_data('users', $datos, array('id' => $id));
            $this->session->set_flashdata('msg', 'El usuario ha sido actualizado.');
            echo json_encode($respuesta);
        }        
    }

    //Funciones alternas 
    function usuario_existente(){
        if (!$this->tank_auth->is_logged_in() || $this->tank_auth->get_rol()!=0) {           
            redirect('/auth/login/');
        }
        $id = $this->input->post('id');
        $user = $this->input->post('user');  
        $respuesta = 0;  
       //Evitar usuarios repetidos
        if ($id != "") {
            $num_row = $this->db->where( array('username' => $user, 'id !=' => $id))->get('users')->num_rows();
        } else {
            $num_row = $this->db->where('username',$user)->get('users')->num_rows();
        }
        
        if ($num_row>0) {                
            $respuesta = 1;  
        } 
        echo json_encode($respuesta);  
    } 

    function correo_existente()
    {
        if (!$this->tank_auth->is_logged_in() || $this->tank_auth->get_rol()!=0) {           
            redirect('/auth/login/');
        }
        $respuesta = 0;
        $id = $this->input->post('id');
        $email = $this->input->post('correo');
        //Evitar correos repetidos
        if ($id != "") {
            $num_row = $this->db->where( array('email' => $email, 'id !=' => $id))->get('users')->num_rows();
        } else {
            $num_row = $this->db->where('email',$email)->get('users')->num_rows();
        }
        
        if ($num_row>0) {                
            $respuesta = 1;  
        } 
        echo json_encode($respuesta);
    }

    function clave_actual(){
        $actual_user = $this->tank_auth->get_user_id();        
        $old_password = $this->funciones_generales_model->get_table_data('users', array('id' => $actual_user));
        //Guarda clave antigua
        foreach ($old_password as $key => $value) {
            $old_password = $value['password'];
        }
        //Captura clave de modal
        $new_password = $this->input->post('inputValue');       
        //Crear hash (Doubts)
        $hasher = new PasswordHash(
                $this->ci->config->item('phpass_hash_strength', 'tank_auth'),
                $this->ci->config->item('phpass_hash_portable', 'tank_auth')
            );        
        $respuesta = 0;
        // Compara clave antigua con actual
        if ($hasher->CheckPassword($new_password, $old_password)) {  
            $respuesta = 1;
        }
        echo json_encode($respuesta);
    }

    function change_pasword(){        
        $actual_user = $this->tank_auth->get_user_id();
        //Captura nueva clave de modal
        $new_password = $this->funciones_generales_model->encriptar_data($this->input->post('inputValue'));
        $respuesta = 0;
        if($this->funciones_generales_model->udpate_data('users', array('password' => $new_password), array('id' => $actual_user))>0){
            $respuesta = 1;
        }
        echo json_encode($respuesta);
    }
    
    public function _cargarView($vista, $datos = array())
    {           
        $data['vista'] = $vista;
        $data['datos'] = $datos;        
        $this->load->view('template', $data);   
    }   
}
?>