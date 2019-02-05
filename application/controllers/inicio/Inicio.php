<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Inicio extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		}
	}

	function index()
	{
		$data['titulo'] = 'Inicio';			
		$this->_cargarView('inicio/inicio', $data);			
	}

	public function _cargarView($vista, $datos = array())
	{			
		$data['vista'] = $vista;
		$data['datos'] = $datos;		
		$this->load->view('template', $data);	
	}	
}