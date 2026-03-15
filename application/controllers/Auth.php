<?php

Class Auth extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model('Auth_model');
	}

	//Muestra la vista del Login
	public function index() {
		$this->load->view('auth/login');
	}


	function load_data_view($view)
    {
    	//Precarga todos los datos con los que la vista debe iniciar
    	$this->load->model('MarketPlace_model');
        $data['_view'] = $view;
		$this->load->view('layouts/main',$data);
    }

	//Proceso de autenticación Login
	public function login() {

		$this->form_validation->set_rules('txt_username', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('txt_password', 'Password', 'trim|required|xss_clean');

		if ($this->form_validation->run() == FALSE) { //Si No se cumple la validación

			//Si autenticamos vamos a la vista principal
			//Sino nos devuelve al login
			//Esto es para el caso de si la sesión aún está activa
			if(isset($this->session->userdata['logged_in'])){
				 //Función propia para cargar la vista indicada con datos precargados
				$this->load_data_view('marketPlace/index');
			}else{
				$this->load->view('auth/login');
			}

		} else {

			//Si se cumple la validación procedemos a comprobar la autenticación
			$data = array(
				'username' => $this->input->post('txt_username'),
				'password' => $this->input->post('txt_password')
			);

			$result = $this->Auth_model->login($data); //Función login del Modelo Auth

			if ($result == TRUE) { //Si autenticamos

				$username = $this->input->post('txt_username');
				$result = $this->Auth_model->get_user_information($username); //Función read_user_information del Modelo Auth

				//leemos los datos del usuario auntenticado y los ingresamos en las Variables de Sesion
				if ($result != false) {
					$session_data = array(
						'logged_in' => TRUE,
						'users_id' => $result[0]->idUsuarios,
						'username' => $result[0]->email,
						'realname' => $result[0]->nombre,
						'photo' => $result[0]->foto_perfil,
						'tipo' => $result[0]->tipo,
					);

					// Agregamos la infomación del usuario en forma de arreglo a la Variable de Sesion con nombre logged_in
					$this->session->set_userdata('logged_in', $session_data);
					//Función propia para cargar la vista indicada con datos precargados
					redirect('marketPlace/index', 'refresh'); //redireccionamos a la URL raíz para evitar que nos quede auth/login/ en la URL
					$this->load_data_view('marketPlace/index'); //luego cargamos la vista
				}
			} else { //Si No autenticamos regreamos a la vista Login con un mensaje de error seteado
				$data = array(
					'error_message' => 'Usuario o Contraseña incorrectos'
				);

				$this->load->view('auth/login', $data);
			}
		}
	}

	//Proceso de Logout 
	public function logout() {

		// Removemos los datos de la sesion
		$sess_array = array(
			'logged_in' => FALSE,
			'username' => '',
		);
		$this->session->unset_userdata('logged_in', $sess_array);
		$this->session->sess_destroy();
		$data['message_display'] = 'Has cerrado tu sesión de forma exitosa.';
		redirect('marketPlace/index', 'refresh'); //redireccionamos a la URL raíz para evitar que nos quede auth/login/ en la URL
		$this->load_data_view('marketPlace/index'); //luego cargamos la vista
	}

}

?>