<?php
class Tienda extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tienda_model');
        $this->load->model('MarketPlace_model');
        $this->load->model('Producto_model');
        $this->load->library('session');
    }

    function index($tienda_id,$productos_data = array())
    {    
        //Se envian datos al view de tienda.
        if ($productos_data == null) {
            $data['productos'] = $this->Tienda_model->get_productos_tienda($tienda_id);     
        }
        else {
            $data['productos'] = $productos_data;
        }

        $data['categorias'] = $this->Tienda_model->get_all_categorias(); 
    
        $data['tienda'] = $this->Tienda_model->get_datos_tienda($tienda_id); 
        $data['redes'] = $this->Tienda_model->get_redes_tienda($tienda_id); 
        $data['fotos'] = $this->Tienda_model->get_all_fotos_productos(); 
        $data['suscripciones'] = $this->Tienda_model->get_suscripciones($tienda_id); 
        $data['usuarios_deseos'] = $this->Tienda_model->get_usuarios_deseos($tienda_id); 
        $data['calificacion'] = $this->Tienda_model->promedio_calificacion_tienda($tienda_id);

        if(!isset($data['calificacion']['calificacionT'])){
            $data['calificacion']['calificacionT']=5;
        }

        $data['_view'] = 'marketPlace/tienda';
        $this->load->view('layouts/main',$data);
    }

    //Permite agregar un nuevo producto que esté relacionado con el id de la tienda.
    function agregar_producto($tienda_id) {
        
        $this->load->library('form_validation');
        $this->load->helper('date');

        $this->form_validation->set_rules('txt_nombre','Nombre','required|max_length[128]');
        $this->form_validation->set_rules('txt_descripcion','Descripcion','required|max_length[128]');
        $this->form_validation->set_rules('txt_disponibles','Disponibles','required');
        $this->form_validation->set_rules('txt_ubicacion','Ubicacion','required|max_length[128]');
        $this->form_validation->set_rules('txt_precio','Precio','required');
        $this->form_validation->set_rules('txt_tiempo_envio','Tiempo de envio','required');
        $this->form_validation->set_rules('txt_costo_envio','Costo de envio','required');
                
        if (empty($_FILES['txt_foto']['name']))
        {
            $this->form_validation->set_rules('txt_foto', 'Foto del producto', 'required');
        }

        $format = "%Y-%m-%d";

        if($this->form_validation->run())     
        {               
            $params = array(
				'nombre' => $this->input->post('txt_nombre'),				
				'descripcion' => $this->input->post('txt_descripcion'),
                'disponibles' => $this->input->post('txt_disponibles'),
                'ubicacion' => $this->input->post('txt_ubicacion'),
                'fecha_publicacion' =>  mdate($format),
                'precio' => $this->input->post('txt_precio'),
                'tiempo_envio' => $this->input->post('txt_tiempo_envio'),
                'costo_envio' => $this->input->post('txt_costo_envio'),
                'Usuarios_id' => $tienda_id,
            );
            
            $product_id = $this->Producto_model->add_producto($params); //Añade el producto
            $this->upload_photo($product_id);//Carga una foto del producto al servidor

            $this->session->set_flashdata('success', "Producto agregado correctamente.");
            redirect('marketPlace/tienda/'.$tienda_id, 'refresh'); 
        }
        else
        {
            $this->index($tienda_id);           
        }
    }

    //Permite al usuario cliente suscribirse a una tienda
    function suscribirse($tienda) { 
        if(isset($this->session->userdata['logged_in'])) {
        
            $params = array(                
                'tienda_id' => $tienda,
                'cliente_id' => $this->session->userdata['logged_in']['users_id'],                
            );
            
            $usuario_suscrito = $this->Tienda_model->verificar_usuario_suscrito($params);            

            if(!isset($usuario_suscrito)) { //Si el usuario no estaba suscrito a la tienda, lo suscribe
                $this->Tienda_model->add_suscripcion($params);  
                $this->session->set_flashdata('success', "Se ha suscrito a la tienda correctamente.");
            }
            else { //Si el usuario ya estaba suscrito a la tienda, lo desuscribe
                $this->Tienda_model->delete_suscripcion($params);                
                $this->session->set_flashdata('success', "Se ha desuscrito de la tienda correctamente.");
            }                         
        }
        redirect('tienda/index/' . $tienda);
    }

    function reportar_abuso($tienda) { 

        $this->load->library('form_validation');
        $this->form_validation->set_rules('txt_descripcion','Descripcion','required|max_length[128]');

        if($this->form_validation->run())     
        {
            if ( $this->input->post('txt_tipo_d') != '0' ) { //Que se haya seleccionado una opcion del combo box de tipo de denuncia
                if(isset($this->session->userdata['logged_in'])) {
                    
                    $params = array(                
                        'detalle' => $this->input->post('txt_descripcion'),
                        'tipo' => $this->input->post('txt_tipo_d'),
                        'cliente_id' => $this->session->userdata['logged_in']['users_id'],                
                        'tienda_id' => $tienda,                
                    );

                    $denuncia = $this->Tienda_model->verifica_denuncia($params); //Verifica si ya existen denuncias por parte de este usuario a esta tienda.
        
                    if(!isset($denuncia)) { //Si el usuario aun no habia denunciado a esta tienda, establece la denuncia
                        $this->Tienda_model->add_denuncia($params);   
                        $this->session->set_flashdata('success', "Reporte de abuso enviado correctamente.");
                    }
                    else { //Si el usuario ya habia denunciado se notifica                                 
                        $this->session->set_flashdata('success', "Ya se ha reportado a esta tienda anteriormente.");
                    }                         

                    //Realiza el conteo de denuncias de la tienda
                    $cantidad_denuncias = $this->Tienda_model->verifica_cantidad_denuncias($tienda);                      
                    if ($cantidad_denuncias['COUNT(denuncias.idDenuncias)'] == 10) {
                        
                        $params = array(                
                            'estado' => 'Inactivo',                            
                        );

                        $this->Tienda_model->deshabilita_tienda($tienda,$params);
                    }                    
                }
            }
                    
            redirect('tienda/index/' . $tienda);
        }
        else
        {
            $this->index($tienda);           
        }        
    }
    function buscar($tienda){ //Buscar productos dentro de la tienda
        if($this->input->post('txt_categoria') != '0') //Si quiere buscar productos por categoria
        {
            $categoria = $this->MarketPlace_model->get_categoria($this->input->post('txt_categoria')); //Obtiene los datos de la categoria seleccionada

            $productos = $this->Tienda_model->buscar_productos_categoria($tienda,$this->input->post('txt_buscar'), $categoria); //Busca los productos que pertenezcan a esa categoria y que coincidan con el parametro de busqueda si especifico
            
            if($productos != null) { //Si se encontraron productos, se cargan los datos en el view
                $this->index($tienda,$productos);  
            }
            else { //Si no se encontraron productos.
                $this->session->set_flashdata('error', "No se encontraron productos.");
                redirect('tienda/index/'.$tienda,'refresh');
                $this->index($tienda);    
            }  
        }
        else { //Si no se selecciono una categoria como parametro de busqueda
            if($this->input->post('txt_buscar') != null) {

                $productos = $this->Tienda_model->buscar_productos($tienda,$this->input->post('txt_buscar')); //Se buscan los productos tomando en cuenta el parametro de busqueda por nombre

                if($productos != null){ //Si se encontraron productos, se cargan los datos en el view
                    $this->index($tienda,$productos);
                }
                else{     //Si no se encontraron productos.                   
                    $this->session->set_flashdata('error', "No se encontraron productos.");
                    redirect('tienda/index/'.$tienda,'refresh');
                    $this->index($tienda);    
                }                    
            }
            else{//Si no se especifico ningun parametro de busqueda
                $this->session->set_flashdata('error', "Digite parámetros de búsqueda.");
                redirect('tienda/index/'.$tienda,'refresh');
                $this->index($tienda);    
            }
        }
    }

    //Permite cargar una nueva imagen al servidor, la imagen se renombra con el id del producto al que pertenece junto con el numero de fotos que tiene
    function upload_photo($product_id)
    {
        //Obtiene la cantidad de fotos que tiene el producto y le suma 1 para obtener así el numero de la nueva imagen
        $num_foto = count($this->Producto_model->get_fotos_producto($product_id)) + 1; 

        $config['upload_path']          = './resources/photos/products/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 2000; //2MB
        $config['file_name']           = $product_id.$num_foto;
        $config['overwrite']            = true;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('txt_foto'))
        {
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('error', $error['error']);

        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $params = array(
                'nombre' => $this->upload->data('file_name'),
                'Productos_id' => $product_id,
            );

            $this->Producto_model->add_foto($params);

            $this->session->set_flashdata('success', "Archivo cargado al sistema exitosamente.");
        }

        redirect('marketPlace/index/');
    }
}