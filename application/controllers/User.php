<?php

class User extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('User_model');
    } 

    function index()
    {
        $data['redes'] = $this->User_model->get_redes_usuario($this->session->userdata['logged_in']['users_id']); 
        $data['_view'] = 'marketPlace/index';
        $this->load->view('layouts/main',$data);
    }

    //Seccion donde se le permite al usuario registrarse al sistema
    function add()
    {   
        $this->load->library('form_validation');
        $this->form_validation->set_rules('txt_clave','Contraseña','required|max_length[128]');
		$this->form_validation->set_rules('txt_nombre','Nombre','required|max_length[64]');
		$this->form_validation->set_rules('txt_correo','Correo','required|max_length[64]');
		$this->form_validation->set_rules('txt_cedula','Cedula','required|max_length[64]');
		$this->form_validation->set_rules('txt_telefono','Telefono','required|max_length[64]');
		$this->form_validation->set_rules('txt_pais','Pais','required|max_length[64]');
		$this->form_validation->set_rules('txt_provincia','Provincia','required|max_length[64]');
		$this->form_validation->set_rules('txt_tipo','Tipo','required');
		
		
		if($this->form_validation->run())     
        {   
            $params = array(
				'email' => $this->input->post('txt_correo'),
				'password' => password_hash($this->input->post('txt_clave'), PASSWORD_BCRYPT),
				'nombre' => $this->input->post('txt_nombre'),
                'foto_perfil' => 'unknown.jpg',
                'cedula' => $this->input->post('txt_cedula'),
                'telefono' => $this->input->post('txt_telefono'),
                'pais' => $this->input->post('txt_pais'),
                'provincia' => $this->input->post('txt_provincia'),
                'tipo' => $this->input->post('txt_tipo'),
                'estado' => 'Activo',
            );
            
            $user_id = $this->User_model->add_user($params);
            
            $data['message_display'] = 'Te has registrado exitosamente.';
            $this->load->view('auth/login', $data);
        }
        else
        {
            $data['_view'] = 'user/add';
            $this->load->view('layouts/main',$data);
        }
    }  

    //Seccion donde se le permite al usuario editar sus datos personales
    function edit($users_id)
    {   
        //Se cargan algunos datos a la vista de user/edit
        $data['redes'] = $this->User_model->get_redes_usuario($this->session->userdata['logged_in']['users_id']); 
        $data['direcciones'] = $this->User_model->get_direcciones_usuario($this->session->userdata['logged_in']['users_id']); 
        $data['metodos_pago'] = $this->User_model->get_metodos_pago_usuario($this->session->userdata['logged_in']['users_id']); 
        $data['user'] = $this->User_model->get_user($users_id);
        
        if(isset($data['user']['idUsuarios']) && $this->session->userdata['logged_in']['users_id'] == $data['user']['idUsuarios'])
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('txt_nombre','Nombre','required|max_length[64]');
            $this->form_validation->set_rules('txt_correo','Correo','required|max_length[64]');
            $this->form_validation->set_rules('txt_cedula','Cedula','required|max_length[64]');
            $this->form_validation->set_rules('txt_telefono','Telefono','required|max_length[64]');
            $this->form_validation->set_rules('txt_pais','Pais','required|max_length[64]');
            $this->form_validation->set_rules('txt_provincia','Provincia','required|max_length[64]');            
            
            if($this->form_validation->run())    
            {   
                $params = array(
                    'email' => $this->input->post('txt_correo'),
                    'nombre' => $this->input->post('txt_nombre'),
                    'cedula' => $this->input->post('txt_cedula'),
                    'telefono' => $this->input->post('txt_telefono'),
                    'pais' => $this->input->post('txt_pais'),
                    'provincia' => $this->input->post('txt_provincia'),
                    
                );

                $this->User_model->update_user($users_id,$params);

                $this->session->set_flashdata('success', "Tus datos de usuario se han actualizado. Vuelve a autenticarte para ver los cambios.");

                $data['_view'] = 'user/edit';
                $this->load->view('layouts/main',$data);
            }
            else
            {
                $data['_view'] = 'user/edit';
                $this->load->view('layouts/main',$data);
            }
        } else {       
            redirect('marketPlace/index/');
        }
    } 
    
    //Elimina los datos del usuario especificado del sistema
    function delete($users_id)
    {   
        $data['user'] = $this->User_model->get_user($users_id);

        if($this->session->userdata['logged_in']['users_id'] == $data['user']['users_id'])      
            $this->User_model->delete_user($users_id);

        $this->session->sess_destroy();
        $data['message_display'] = 'Tu cuenta se ha eliminado exitosamente. ¡Vuelve pronto!';
        $this->load->view('auth/login', $data);
    }

    //Permite asignar una nueva direccion de envio al usuario
    function agregar_direccion()
    {   
        $this->load->library('form_validation');
        $this->form_validation->set_rules('txt_pais_d','Pais','required|max_length[128]');
        $this->form_validation->set_rules('txt_provincia_d','Provincia','required|max_length[128]');
        $this->form_validation->set_rules('txt_casillero','Casillero','required|max_length[128]');
        $this->form_validation->set_rules('txt_postal','Postal','required|max_length[128]');
        $this->form_validation->set_rules('txt_observacion','Observacion','required|max_length[128]');
		
		if($this->form_validation->run())     
        {               
            $params = array(
                'pais' => $this->input->post('txt_pais_d'),                    
                'provincia' => $this->input->post('txt_provincia_d'),                    
                'casillero' => $this->input->post('txt_casillero'),                    
                'postal' => $this->input->post('txt_postal'),                    
                'observaciones' => $this->input->post('txt_observacion'),                    
                'estado' => 'Activo',                    
                'Usuarios_id' =>  $this->session->userdata['logged_in']['users_id'],                    
            );
                    
            $this->User_model->add_direccion_envio($params);
            
            $this->session->set_flashdata('success', "Dirección de envío agregada correctamente");                
                    
        }
        else
        {
            $this->session->set_flashdata('error', "Proporcione los parametros necesarios para ingresar la dirección de envío."); 
        }

        redirect('user/edit/'.$this->session->userdata['logged_in']['users_id'],'refresh');
    } 
    
    //Permite asignar una nueva red social al usuario
    function agregar_red()
    {   
        $this->load->library('form_validation');
        $this->form_validation->set_rules('txt_url_red','Url','required|max_length[128]');
		
		if($this->form_validation->run())     
        {   
            if ($this->input->post('txt_nombre') != '0'){
                $params = array(
                    'url' => $this->input->post('txt_url_red'),
                    'Redes_Sociales_id' => $this->input->post('txt_red_social'),
                    'Usuarios_id' =>  $this->session->userdata['logged_in']['users_id'],                    
                );
                       
                $this->User_model->add_red_social($params);
                
                $this->session->set_flashdata('success', "Red social agregada correctamente");                
            }            
        }
        else
        {
            $this->session->set_flashdata('error', "Proporcione los parametros necesarios para ingresar la red social."); 
        }

        redirect('user/edit/'.$this->session->userdata['logged_in']['users_id'],'refresh');
    } 

    //Permite asignar un nuevo metodo de pago al usuario
    function agregar_metodo_pago()
    {   
        $data['user'] = $this->User_model->get_user($this->session->userdata['logged_in']['users_id']);
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('txt_titular_tarjeta','Titular de tarjeta','required|max_length[50]');
        $this->form_validation->set_rules('txt_numero_tarjeta','Numero de tarjeta','required|max_length[16]');
        $this->form_validation->set_rules('txt_codigo_cvv','Codigo CVV','required|max_length[3]');
        $this->form_validation->set_rules('txt_vencimiento','Vencimiento','required');
		
		if($this->form_validation->run())     
        {   
            $metodo_pago = $this->User_model->get_numero_tarjeta($this->input->post('txt_numero_tarjeta')); //Verifica si existe el numero de tarjeta

            if (!isset($metodo_pago)) { //Si no existe, se agrega
                $params = array(
                    'titular_tarjeta' => $this->input->post('txt_titular_tarjeta'),
                    'numero_tarjeta' => $this->input->post('txt_numero_tarjeta'),                
                    'codigo_cvv' => password_hash($this->input->post('txt_codigo_cvv'), PASSWORD_BCRYPT),             
                    'saldo' => 1000,
                    'estado' => 'Activo',
                    'vencimiento' => $this->input->post('txt_vencimiento'),
                    'Usuarios_id' =>  $data['user']['idUsuarios'],                    
                );
                        
                $this->User_model->add_metodo_pago($params);
                $this->session->set_flashdata('success', "Metodo de pago agregado correctamente."); 
            }
            else{
                $this->session->set_flashdata('error', "El numero de tarjeta proporcionado ya se encuentra registrado."); 
            }
        }
        else
        {
            $this->session->set_flashdata('error', "Proporcione los parametros necesarios para ingresar el metodo de pago."); 
        }

        redirect('user/edit/'.$this->session->userdata['logged_in']['users_id'],'refresh');
    } 

    //Permite eliminar una red social de algun usuario
    function eliminar_red($red_id)
    {   
        $data['redes'] = $this->User_model->get_redes_sociales_usuario($red_id);
        
        if($this->session->userdata['logged_in']['users_id'] == $data['redes']['Usuarios_id']) {   
            $this->User_model->delete_red($red_id);
            $this->session->set_flashdata('success', "Red social eliminada correctamente.");
        }
                
        redirect('user/edit/'.$this->session->userdata['logged_in']['users_id'],'refresh');
    }

    //Permite eliminar un metodo de pago de un usuario
    function eliminar_metodo_pago($metodo_pago_id)
    {   
        $data['metodo_pago'] = $this->User_model->get_metodo_pago($metodo_pago_id);
        
        if($this->session->userdata['logged_in']['users_id'] == $data['metodo_pago']['Usuarios_id']) {  
            
            $params = array(
                'estado' => 'Inactivo',                
            );

            $this->User_model->update_metodo_pago($metodo_pago_id,$params);
            $this->session->set_flashdata('success', "Metodo de pago eliminado correctamente.");
        }
                
        redirect('user/edit/'.$this->session->userdata['logged_in']['users_id'],'refresh');
    }

    //Permiite eliminar una direccion de envio de un usuario
    function eliminar_direccion($direccion_id)
    {   
        $data['direccion'] = $this->User_model->get_direccion($direccion_id);
        
        if($this->session->userdata['logged_in']['users_id'] == $data['direccion']['Usuarios_id']) {   

            $params = array(
                'estado' => 'Inactivo',                
            );

            $this->User_model->update_direccion($direccion_id,$params);
            $this->session->set_flashdata('success', "Dirección de envío eliminada correctamente.");
        }
                
        redirect('user/edit/'.$this->session->userdata['logged_in']['users_id'],'refresh');
    }

    //Permite cargar imagenes al servidor, para la foto de perfil del usuario
    function upload_photo($users_id)
    {
        $config['upload_path']          = './resources/photos/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 2000; //2MB
        $config['file_name']           = $users_id;
        $config['overwrite']            = true;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('txt_file'))
        {
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('error', $error['error']);

        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $params = array(
                'foto_perfil' => $this->upload->data('file_name'),
            );

            $this->User_model->update_user($users_id,$params);

            $this->session->set_flashdata('success', "Archivo cargado al sistema exitosamente.");
        }

        redirect('user/edit/'. $users_id);
    }
    
}
