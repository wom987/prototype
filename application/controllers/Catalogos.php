<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalogos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		}						
	}
	
	public function categoria()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_subject('Categoría');
			$crud->set_table('ca_categorias');
			$crud->display_as('ca_nombre', 'Categoría');
			$crud->required_fields('ca_nombre');
			$crud->columns('ca_nombre');
			$crud->set_rules('ca_nombre', 'Categoría', 'required|callback_categoria_check');	
			$crud->callback_before_delete(array($this,'category_before_delete'));			
			if ($this->tank_auth->get_rol()==1) {				
				$crud->unset_delete();
				$crud->unset_edit();
			}
			$state = $crud->getState();
			if($state == 'delete'){
				$crud->set_lang_string('delete_error_message', 'La categoría ha sido asociada a un producto, no puede ser eliminada.');
			}
			$data['crud'] = $crud->render();			
			$data['titulo'] = 'Categorías';

			$this->_cargarView('catalogos/categoria', $data);	

		} catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function categoria_check($ca_nombre)
	{   		
		$segmentsCount = $this->uri->total_segments(); 
		$id = intval($this->uri->segment($segmentsCount));	

		if ($id!=0){
			$num_row = $this->db->where(array('ca_nombre'=>$ca_nombre,'ca_id !='=>$id))->get('ca_categorias')->num_rows();
		} 
		else{
			$num_row = $this->db->where('ca_nombre',$ca_nombre)->get('ca_categorias')->num_rows();			
		}

		if($num_row > 0)
		{
			$this->form_validation->set_message('categoria_check','La categoría ya existe.');			
			return false;
		}
		else{
			return true;
		}
	}

	public function category_before_delete($primary_key){
		$num_row = $this->db->where(array('pr_categoria'=>$primary_key))->get('pr_productos')->num_rows();
		if($num_row > 0){
			return false;
		} else{
			return true;
		}
	}

	public function departamento()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_subject('Departamento');
			$crud->set_table('de_departamentos');
			$crud->display_as('de_nombre', 'Departamento')
				 ->display_as('de_telefono', 'Teléfono')
			     ->display_as('de_jefe','Jefe directo');
			$crud->required_fields('de_nombre','de_jefe');
			$crud->columns('de_nombre','de_jefe', 'de_telefono');
			$crud->callback_before_delete(array($this,'department_before_delete'));
			if ($this->tank_auth->get_rol()==1) {				
				$crud->unset_delete();
				$crud->unset_edit();
			}
			$state = $crud->getState();
			if($state == 'delete'){
				$crud->set_lang_string('delete_error_message', 'El departamento ya posee instalaciones, no puede ser eliminado.');
			}

			$data['crud'] = $crud->render();			
			$data['titulo'] = 'Departamentos';

			$this->_cargarView('catalogos/departamento', $data);	

		} catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function department_before_delete($primary_key){
		$num_row = $this->db->where(array('do_departamento'=>$primary_key))->get('do_detalle_operaciones')->num_rows();
		if($num_row > 0){
			return false;
		} else{
			return true;
		}
	}

	public function producto()
	{
		try{
			$crud = new grocery_CRUD();			

			$crud->set_subject('Producto');
			$crud->set_table('pr_productos');			
			$crud->display_as('pr_categoria', 'Categoría')
				 ->display_as('pr_nombre', 'Producto')
				 ->display_as('pr_codigo', 'Código');
			$crud->required_fields('pr_nombre','pr_codigo');
			$crud->columns('pr_categoria','pr_codigo','pr_nombre');
			$crud->set_relation('pr_categoria', 'ca_categorias', 'ca_nombre');
			$crud->set_rules('pr_codigo', 'Código', 'required|callback_codigo_check');	
			$crud->callback_before_delete(array($this,'product_before_delete'));		
			if ($this->tank_auth->get_rol()==1) {				
				$crud->unset_delete();
				$crud->unset_edit();
			}
			$state = $crud->getState();
			if($state == 'delete'){
				$crud->set_lang_string('delete_error_message', 'El producto ya ha sido inventariado, no puede ser eliminado.');
			}

			$data['crud'] = $crud->render();			
			$data['titulo'] = 'Productos';


			$this->_cargarView('catalogos/departamento', $data);	

		} catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function product_before_delete($primary_key){
		$num_row = $this->db->where(array('do_producto'=>$primary_key))->get('do_detalle_operaciones')->num_rows();
		if($num_row > 0){
			return false;
		} else{
			return true;
		}
	}

	public function codigo_check($pr_codigo)
	{   		
		$segmentsCount = $this->uri->total_segments(); 
		$id = intval($this->uri->segment($segmentsCount));	

		if ($id!=0){
			$num_row = $this->db->where(array('pr_codigo'=>$pr_codigo,'pr_id !='=>$id))->get('pr_productos')->num_rows();
		} 
		else{
			$num_row = $this->db->where('pr_codigo',$pr_codigo)->get('pr_productos')->num_rows();			
		}

		if($num_row > 0)
		{
			$this->form_validation->set_message('codigo_check','El código de producto ya existe.');			
			return false;
		}
		else{
			return true;
		}		
	}

	public function proveedor()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_subject('Proveedor');
			$crud->set_table('pr_proveedores');
			$crud->display_as('pr_nombre', 'Nombre')
				 ->display_as('pr_nit', 'NIT')
			     ->display_as('pr_direccion', 'Dirección')
			     ->display_as('pr_correo', 'Correo electrónico')
			     ->display_as('pr_contacto','Contacto')
			     ->display_as('pr_telefono', 'Teléfono')
			     ->display_as('pr_observaciones','Observaciones');
			$crud->required_fields('pr_nombre','pr_nit');
			$crud->columns('pr_nombre','pr_nit');
						$crud->callback_before_delete(array($this,'provider_before_delete'));		

			if ($this->tank_auth->get_rol()==1) {				
				$crud->unset_delete();
				$crud->unset_edit();
			}
			$state = $crud->getState();
			if($state == 'delete'){
				$crud->set_lang_string('delete_error_message', 'Hay productos ingresados para este proveedor, no puede ser eliminado.');
			}

			$data['crud'] = $crud->render();			
			$data['titulo'] = 'Proveedores';

			$this->_cargarView('catalogos/proveedor', $data);	

		} catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function provider_before_delete($primary_key){
		$num_row = $this->db->where(array('op_proveedor'=>$primary_key))->get('op_operaciones')->num_rows();
		if($num_row > 0){
			return false;
		} else{
			return true;
		}
	}

	public function _cargarView($vista, $datos = array())
	{			
		$data['vista'] = $vista;
		$data['datos'] = $datos;		
		$this->load->view('template', $data);	
	}		
}
?>