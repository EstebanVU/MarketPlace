<?php
class Producto extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Producto_model');
        $this->load->model('MarketPlace_model');
        $this->load->library('session');
    }
    function index($producto_id)
    {
        //Se envian datos al view de producto.
        $data['producto'] = $this->Producto_model->get_datos_producto($producto_id); 
        $data['categorias'] = $this->Producto_model->get_all_categorias(); 
        $data['categorias_producto'] = $this->Producto_model->get_categorias_producto($producto_id); 
        $data['fotos'] = $this->Producto_model->get_fotos_producto($producto_id);
        $data['calificacion'] = $this->Producto_model->promedio_calificacion_producto($producto_id);
        $data['comentarios'] = $this->Producto_model->get_comentarios($producto_id);
        if(!isset($data['calificacion']['calificacionP'])){
            $data['calificacion']['calificacionP']=5;
        }
        $arr=array(); //Se llena el array con las respuestas de los comentarios de un producto        
        for ($i=0; $i<sizeof($data['comentarios']); $i++){
            array_push($arr,$this->Producto_model->get_comentarios_resp($data['comentarios'][$i]['idComentarios']));
        }

        $data['respuestas']=$arr;
        $data['_view'] = 'marketPlace/producto';
        $this->load->view('layouts/main',$data);
    }
    //Permite agregar una categoria al producto especificado
    function agregar_categoria_producto($producto_id) {
             
        if($this->input->post('txt_nueva_categoria') != null) { //Si el txt nueva categoria (donde el usuario digita la nueva categoria a agregar) contiene datos

            $categoria_existente = $this->Producto_model->verificar_categoria($this->input->post('txt_nueva_categoria')); //Se verifica que la categoria no exista

            if(!isset($categoria_existente)){ // Si la varible no contiene datos, la categoria no existía, por lo que se puede proceder a agregarla
                $params = array(
                    'nombre' => $this->input->post('txt_nueva_categoria'),				                    
                );
                
                $id_categoria = $this->Producto_model->add_categoria($params); //Se agrega la nueva categoria

                //Se procede a asignarle la nueva categoria al producto que fue especificado
                $params2 = array(
                    'Categorias_id' => $id_categoria,				
                    'Productos_id' => (int)$producto_id,
                );

                $this->Producto_model->add_categoria_producto($params2); //Se añade la nueva categoria al producto
            }
            else{ //Si la categoria ya existía, se procede a asignarsela directamente al producto

                $params = array(
                    'Categorias_id' => $categoria_existente['idCategorias'],				
                    'Productos_id' => $producto_id,
                );

                $categoria_existente = $this->Producto_model->verificar_categoria_producto($params); //Verifica si el producto ya tiene asignada esa categoría

                if(!isset($categoria_existente)){ //Si no la tiene asiganada, se la asigna
                    $this->Producto_model->add_categoria_producto($params);
                }  
            }  
        }
        else if ($this->input->post('txt_categoria') != "0") //En caso de que el txt no contenga datos, se evalua para ver si el usuario eligió una de las categorías ya establecida
        {   
            //Si eligió alguna de las opciones, se procede a asignarsela al producto
            $params = array(
                'Categorias_id' => $this->input->post('txt_categoria'),				
                'Productos_id' => $producto_id,
            );

            $categoria_existente = $this->Producto_model->verificar_categoria_producto($params);//Verifica si el producto ya tiene asignada esa categoría

            if(!isset($categoria_existente)){//Si no la tiene asiganada, se la asigna
                $this->Producto_model->add_categoria_producto($params);
            }                 
            
            $this->session->set_flashdata('success', "Categoría asignada correctamente.");
        }           
        
        redirect('producto/index/'.$producto_id, 'refresh'); 
    }
    //Permite eliminar una de las categorias que tiene el producto asignado
    function eliminar_categoria_producto($producto_id) {

        $params = array(
            'Categorias_id' => $this->input->post('txt_categoria'),				
            'Productos_id' => $producto_id,
        );
        //Se verifica si el producto tiene esa categoria
        $categoria_existente = $this->Producto_model->verificar_categoria_producto($params);

        if(isset($categoria_existente)){ //Si el producto tiene esa categoría, se procede a eliminarsela
            $this->Producto_model->delete_categoria_producto($params);
        }                 

        $this->session->set_flashdata('success', "Categoría eliminada correctamente.");
        redirect('producto/index/'.$producto_id, 'refresh');                  
    }
    
    //Permite editar los datos de un producto según el id
    function editar_producto($product_id) {
      
        $this->load->library('form_validation');

        $this->form_validation->set_rules('txt_nombre','Nombre','required|max_length[128]');
        $this->form_validation->set_rules('txt_descripcion','Descripcion','required|max_length[128]');
        $this->form_validation->set_rules('txt_disponibles','Disponibles','required');
        $this->form_validation->set_rules('txt_ubicacion','Ubicacion','required|max_length[128]');
        $this->form_validation->set_rules('txt_precio','Precio','required');
        $this->form_validation->set_rules('txt_tiempo_envio','Tiempo de envio','required');
        $this->form_validation->set_rules('txt_costo_envio','Costo de envio','required');

        if($this->form_validation->run())     
        {  
            $data['user'] = $this->Producto_model->get_datos_producto($product_id); //Obtiene los datos del producto
            
            //Verifica que el producto pertenezca al mismo usuario que el que tiene la sesión iniciada en la página
            if(isset($data['user']) && $this->session->userdata['logged_in']['users_id'] == $data['user']['Usuarios_id']) 
            {
                $params = array(
                    'nombre' => $this->input->post('txt_nombre'),				
                    'descripcion' => $this->input->post('txt_descripcion'),
                    'disponibles' => $this->input->post('txt_disponibles'),
                    'ubicacion' => $this->input->post('txt_ubicacion'),
                    'precio' => $this->input->post('txt_precio'),
                    'tiempo_envio' => $this->input->post('txt_tiempo_envio'),
                    'costo_envio' => $this->input->post('txt_costo_envio'),
                );
                
                $this->Producto_model->update_producto($product_id,$params);//Actualiza los datos del producto
    
                $this->session->set_flashdata('success', "Producto actualizado correctamente.");
                redirect('producto/index/'.$product_id, 'refresh'); 
            }
            else{
                $this->session->set_flashdata('error', "No posee los permisos necesarios para actualizar el producto.");
                redirect('producto/index/'.$product_id, 'refresh'); 
            }            
        }    
        else
        {
            $this->index($product_id);
        }  
    }

    //Permite eliminar un producto según su id
    function eliminar_producto($product_id){

        $data['user'] = $this->Producto_model->get_datos_producto($product_id); 
        
        //Verifica que el producto pertenezca al mismo usuario que el que tiene la sesión iniciada en la página
        if(isset($data['user']) && $this->session->userdata['logged_in']['users_id'] == $data['user']['Usuarios_id']) {

            $params = array(                
                'idProductos' => $product_id,
            );

            $this->Producto_model->delete_producto($params);
                   
            $this->session->set_flashdata('success', "Producto eliminado del carrito correctamente.");
        }

        redirect('marketPlace/index/');
    }
    //Se agrega un respuesta 
    function agregar_comentario_respuesta($product_id, $comentario)
    {   
        $this->load->library('form_validation'); 
        $this->form_validation->set_rules('text_respuesta','Respuesta','required');   
        if(isset($this->session->userdata['logged_in'])) {
            if($this->form_validation->run())     
            {  
                $params = array(     
                    'descripcion'  => $this->input->post('text_respuesta'),         
                    'Productos_id' => $product_id,
                    'Usuarios_id' => $this->session->userdata['logged_in']['users_id'],
                );
                $variable = $this->MarketPlace_model->add_comentario($params);
                $respuesta = array(     
                    'Comentarios_id' => $variable
                );
                $this->Producto_model->update_comentarios($comentario, $respuesta);
                $this->session->set_flashdata('success', "El cometario fue enviado correctamente");
            }else{
                $this->session->set_flashdata('error', "Asigne los parametros necesarios");
            }
        redirect('producto/index/' . $product_id);
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