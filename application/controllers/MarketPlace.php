<?php
class MarketPlace extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('MarketPlace_model');
        $this->load->model('User_model');
        $this->load->library('session');
    }
    //Se cargan los datos necesarios para el view de tienda.
    function index($tiendas_data = array(),$productos_data = array())
    {
        if($tiendas_data == null){ 
            $data['tiendas'] = $this->MarketPlace_model->get_all_tiendas(); 

            $data['productos'] = $this->MarketPlace_model->get_productos_destacados();
        } else { 
            $data['tiendas'] = $tiendas_data; 
            $data['productos'] = $productos_data;             
        }

        $data['fotos'] = $this->MarketPlace_model->get_all_fotos_productos(); 
        $data['categorias'] = $this->MarketPlace_model->get_all_categorias();

        $data['_view'] = 'marketPlace/index';
        $this->load->view('layouts/main', $data);
    }
    
    //Se cargan los datos necesarios para el view de carrito.
    function carrito($usuario_id)
    {           
        $data['productos_carrito'] = $this->MarketPlace_model->get_carrito($usuario_id); 
        $data['productos_deseos'] = $this->MarketPlace_model->get_deseos($usuario_id); 
        $data['categorias'] = $this->MarketPlace_model->get_all_categorias(); 
        $data['fotos'] = $this->MarketPlace_model->get_all_fotos_productos();
        $data['metodos_pago'] = $this->User_model->get_metodos_pago_usuario($this->session->userdata['logged_in']['users_id']);
        $data['direcciones'] = $this->User_model->get_direcciones_usuario($this->session->userdata['logged_in']['users_id']); 

        //Realiza el calculo del precio total que tienen todos los productos del carrito
        $precio_total = 0;
        foreach($data['productos_carrito'] as $p){        
            $precio_total += ($p['precio'] * $p['cantidad']) + $p['costo_envio'];
        }
        
        $data['precio_total'] = $precio_total;

        $data['_view'] = 'marketPlace/carrito';
        $this->load->view('layouts/main',$data);
    }
    //Realiza la busqueda en la pagina principal, tomando en cuenta el tipo(producto, tienda), las diferentes categorías que existen y el parametro de busqueda que proporciona el usuario
    function buscar(){        

        if($this->input->post('txt_tipo') != null && $this->input->post('txt_tipo') == "Tiendas") { //Si quiere buscar tienda
            if($this->input->post('txt_categoria') != '0') //Si quiere buscar una tienda por categoria
            {
                $categoria = $this->MarketPlace_model->get_categoria($this->input->post('txt_categoria')); //Obtiene los datos de la categoria seleccionada

                //Busca las tiendas que vendan productos de esa categoria y que coincidan con el parametro de busqueda (Si se especificó)
                $tiendas = $this->MarketPlace_model->buscar_tiendas_categoria($this->input->post('txt_buscar'), $categoria); 

                if($tiendas != null) { //Si se encontraron tiendas con productos en esa categoria

                    //Se obtienen los productos de cada tienda encontrada, y que pertenezcan a la categoria
                    $productos = array();//Se almacenan todos los productos encontrados en un unico arreglo
                    foreach($tiendas as $r){
                        $productos = array_merge($productos,$this->MarketPlace_model->buscar_productos_tienda_categoria($r,$categoria)); 
                    }

                    $productos = array_unique($productos,SORT_REGULAR); //Elimina datos repetidos
                    $this->index($tiendas,$productos);  //Se envian las tiendas y los productos encontrados al view
                }
                else { //No se encontraron tiendas
                    $this->session->set_flashdata('error', "No se encontraron tiendas.");
                    redirect('marketPlace/buscar','refresh');
                    $this->index();    
                }               
            }
            else { //Si no selecciona ninguna categoria
                if($this->input->post('txt_buscar') != null) { //Verificar que haya escrito algun parametro de busqueda
                    $tiendas = $this->MarketPlace_model->buscar_tiendas($this->input->post('txt_buscar')); //Buscar tiendas que coincidan con el parametro de busqueda
                    if($tiendas != null){ //Si se encuentran tiendas

                        //Se obtienen los productos de cada tienda encontrada
                        $productos = array(); //Se almacenan todos los productos encontrados en un unico arreglo
                        foreach($tiendas as $r){
                            $productos =  array_merge($productos,$this->MarketPlace_model->buscar_productos_tienda($r));
                        }
                        $this->index($tiendas,$productos); //Se envian las tiendas y los productos encontrados al view                    
                    }
                    else{ //Si no se encontraron tiendas
                        $this->session->set_flashdata('error', "No se encontraron tiendas.");
                        redirect('marketPlace/buscar','refresh');
                        $this->index();                            
                    }
                } 
                else {//Si no se especifica el parametro de busqueda
                    $this->session->set_flashdata('error', "Digite parámetros de búsqueda.");
                    redirect('marketPlace/buscar','refresh');
                    $this->index();    
                } 
            }            
        }
        else if ($this->input->post('txt_tipo') != null && $this->input->post('txt_tipo') == "Productos") { //Si quiere buscar productos
            if($this->input->post('txt_categoria') != '0') //Si quiere buscar productos por categoria
            {
                $categoria = $this->MarketPlace_model->get_categoria($this->input->post('txt_categoria')); //Obtiene los datos de la categoria seleccionada

                $productos = $this->MarketPlace_model->buscar_productos_categoria($this->input->post('txt_buscar'), $categoria); //Obtiene todos los productos que pertenezcan a esa categoria
                
                if($productos != null) { //Si existen productos con esa categoria
                    
                    //Se obtienen las tiendas a las que pertenecen los productos
                    $tiendas = array(); //Se almacenan todoa las encontrados en un unico arreglo
                    foreach($productos as $r){
                        $tiendas = array_merge($tiendas,$this->MarketPlace_model->buscar_tienda_producto_categoria($r,$categoria));
                    }

                    $tiendas = array_unique($tiendas,SORT_REGULAR); //Elimina datos repetidos
                    $this->index($tiendas,$productos); //Se envian las tiendas y los productos encontrados al view
                }
                else { //Si no se encontraron productos con esa categoria
                    $this->session->set_flashdata('error', "No se encontraron productos.");
                    redirect('marketPlace/buscar','refresh');
                    $this->index();    
                }  
            }
            else { //Si no especifico una categoria para buscar los productos
                if($this->input->post('txt_buscar') != null) { //Verificar que haya escrito algun parametro de busqueda
                    
                    $productos = $this->MarketPlace_model->buscar_productos($this->input->post('txt_buscar'));//Buscar los productos que coincidan con el parametro de busqueda
                    if($productos != null){ //Si se encontraron productos con ese parametro de busqueda

                          //Se obtienen las tiendas a las que pertenecen los productos
                        $tiendas = array(); //Se almacenan todos las encontrados en un unico arreglo
                        foreach($productos as $r){
                            $tiendas = array_merge($tiendas,$this->MarketPlace_model->buscar_tienda_producto($r));
                        }
                                        
                        $tiendas = array_unique($tiendas,SORT_REGULAR); //Elimina datos repetidos
                        $this->index($tiendas,$productos);//Se envian las tiendas y los productos encontrados al view
                    }
                    else{ //No se encontraron productos con ese parametro de busqueda                  
                        $this->session->set_flashdata('error', "No se encontraron productos.");
                        redirect('marketPlace/buscar','refresh');
                        $this->index();    
                    }                    
                }
                else{//No se especifico un parametro de busqueda
                    $this->session->set_flashdata('error', "Digite parámetros de búsqueda.");
                    redirect('marketPlace/buscar','refresh');
                    $this->index();    
                }
            }
        }
        else{ //Si no se especifica si desea buscar producto o tienda
            $this->index();   
        }  
        
    }
    //Permite agregar productos al carrito del usuario
    function agregar_carrito($product_id)
    {        
        $this->load->library('form_validation');        
        $this->form_validation->set_rules('txt_cantidad','Cantidad','required');

        $cant_producto = $this->MarketPlace_model->cantidad_producto($product_id);

        if($cant_producto > 0) { //Si la cantidad de productos disponibles para la venta es mayor a 0
            if(isset($this->session->userdata['logged_in'])) { //Si se encuentra con la sesion iniciada
            
                $params = array(                
                    'Productos_id' => $product_id,
                    'Usuarios_id' => $this->session->userdata['logged_in']['users_id'],
                    'cantidad' => $this->input->post('txt_cantidad'),
                );
                
                $producto_existente = $this->MarketPlace_model->verificar_producto_carrito($params); //Verifica si el producto ya se encuentra en el carrito

                if(isset($producto_existente)){ //Si ya existe, aumenta la cantidad de productos en 1
                    $params2 = array(                
                        'cantidad' => $producto_existente['cantidad'] + 1,                        
                    );
                    $this->MarketPlace_model->update_carrito($producto_existente['idCarritos'] , $params2);
                } else { //Si no existe, agrega el producto al carrito
                    $this->MarketPlace_model->add_carrito($params);
                }
                
                $this->session->set_flashdata('success', "Producto agregado al carrito correctamente.");
            }
        }
        else{
            $this->session->set_flashdata('success', "No hay productos disponibles para la venta.");
        }

        redirect('producto/index/' . $product_id);
    }
    //Elimina el producto especifico del carrito del usuario
    function eliminar_carrito($product_id)
    {                  
        if(isset($this->session->userdata['logged_in'])) {
        
            $params = array(                
                'Productos_id' => $product_id,
                'Usuarios_id' => $this->session->userdata['logged_in']['users_id'],
            );
            
            $producto_existente = $this->MarketPlace_model->verificar_producto_carrito($params); //Verifica si el producto ya se encuentra en el carrito

            if(isset($producto_existente)){
                $this->MarketPlace_model->eliminar_carrito($params);
                $this->session->set_flashdata('success', "Producto eliminado del carrito correctamente.");
            }                        
        }

        redirect('marketPlace/carrito/' . $this->session->userdata['logged_in']['users_id']);
    }
    
    //Agrega un nuevo producto a la lista de deseos
    function agregar_deseo($product_id)
    {       
        if(isset($this->session->userdata['logged_in'])) {
        
            $params = array(                
                'Productos_id' => $product_id,
                'Usuarios_id' => $this->session->userdata['logged_in']['users_id'],
                'alerta' => 'N',
            );
            
            $producto_existente = $this->MarketPlace_model->verificar_producto_deseo($params);//Verifica si el producto ya se encuentra en la lista de deseos

            if(!isset($producto_existente)){ //Si el producto no se encuentra en la lista de deseos
                $this->MarketPlace_model->add_deseo($params);
                $this->session->set_flashdata('success', "Producto agregado a la lista de deseos correctamente.");
            } 
            else{//Si el producto ya se encontraba en la lista de deseos
                $this->session->set_flashdata('success', "El producto ya se encontraba en la lista de deseos.");
            }  
        }

        redirect('producto/index/' . $product_id);
    }
    //Elimina el producto especificado de la lista de deseos del usuario
    function eliminar_deseo($product_id)
    {                  
        if(isset($this->session->userdata['logged_in'])) {
        
            $params = array(                
                'Productos_id' => $product_id,
                'Usuarios_id' => $this->session->userdata['logged_in']['users_id'],
            );
            
            $producto_existente = $this->MarketPlace_model->verificar_producto_deseo($params); //Verifica si el producto ya se encuentra en la lista de deseos

            if(isset($producto_existente)){
                $this->MarketPlace_model->eliminar_deseo($params);
                $this->session->set_flashdata('success', "Producto eliminado de la lista de deseos correctamente.");
            }                        
        }

        redirect('marketPlace/carrito/' . $this->session->userdata['logged_in']['users_id']);
    }

    //Permite asignar una calificacion al producto especificado
    function agregar_calificacion($product_id)
    {   
        $this->load->library('form_validation'); 
        $this->form_validation->set_rules('txt_calificacion','Calificacion','required');   

        if(isset($this->session->userdata['logged_in'])) {
            if($this->form_validation->run()&& $this->input->post('txt_calificacion') != '0') //Que se hayan ingresado los parametros necesarios
            {  
                $params = array(     
                    'calificacion'  => $this->input->post('txt_calificacion'),         
                    'Productos_id' => $product_id,
                    'Usuarios_id' => $this->session->userdata['logged_in']['users_id'],
                );
                
                $calificacion_existente = $this->MarketPlace_model->verificar_calificacion($params); //Verifica si ya se califico ese producto anteriormente
                
                if($calificacion_existente==NULL){ //Si no se ha calificado, se agrega una calificacion
                    $this->MarketPlace_model->add_calificacion($params);
                    $this->session->set_flashdata('success', "La calificacion fue enviada correctamente");
                }else{//Si ya se tenia calificado el producto, se actualiza a la nueva calificacion
                    $params = array(     
                        'calificacion'  => $this->input->post('txt_calificacion'),         
                    );
                    $this->MarketPlace_model->update_calificacion($calificacion_existente['idCalificaciones'],$params);
                    $this->session->set_flashdata('success', "La calificacion fue modificada correctamente");
                }
            }else {
                $this->session->set_flashdata('error', "Asigne los parametros necesarios");
            }

            redirect('producto/index/' . $product_id);
        }
    }

    //Se agregar un comentario al producto, ya se de un nuevo comentario o de respuesta
    function agregar_comentario($product_id)
    {   
        $this->load->library('form_validation'); 
        $this->form_validation->set_rules('text_comentario','Comentario','required');   
        if(isset($this->session->userdata['logged_in'])) {
            if($this->form_validation->run())     
            {  
                $params = array(     
                    'descripcion'  => $this->input->post('text_comentario'),         
                    'Productos_id' => $product_id,
                    'Usuarios_id' => $this->session->userdata['logged_in']['users_id'],
                );
                    $this->MarketPlace_model->add_comentario($params);
                    $this->session->set_flashdata('success', "El cometario fue enviado correctamente");
            }else{
                $this->session->set_flashdata('error', "Asigne los parametros necesarios");
            }
        redirect('producto/index/' . $product_id);
        }
    }

    //Permite comprar todos los productos que se encuentran en el carrito
    function comprar_carrito(){

        $this->load->library('form_validation');
        $this->load->helper('date');

        $this->form_validation->set_rules('txt_num_tarjeta','Numero de tarjeta','required|max_length[128]');
        $this->form_validation->set_rules('txt_codigo_cvv_pago','Codigo CVV','required|max_length[128]');
        $this->form_validation->set_rules('txt_direccion','Direccion de envio','required|max_length[128]');
        
        $format = "%Y-%m-%d";

        //Verifica que los datos para realizar la compra esten llenos
        if($this->form_validation->run() && $this->input->post('txt_direccion') != "0" && $this->input->post('txt_num_tarjeta') != "0")     
        {  
            $data = array(				
				'numero_tarjeta' => $this->input->post('txt_num_tarjeta'),
				'codigo_cvv' => $this->input->post('txt_codigo_cvv_pago')
			);
            
            $result = $this->MarketPlace_model->verificar_tarjeta($data); //Verifica que el codigo cvv de la tarjeta ingresado corresponda con el de la bd
            
            if ($result == TRUE) { //Si el codigo cvv es correcto

                //Obtiene los datos de los productos que se encuentran en el carrito
                $productos_carrito = $this->MarketPlace_model->get_carrito($this->session->userdata['logged_in']['users_id']); 

                if ($productos_carrito != null) { //Si existen productos en el carrito
                
                    $tienda_inhabilitada = FALSE; //Variable que va a identificar si existe una tienda inhabilitada
                    $precio_total = 0; //Guarda el costo total de la compra a realizar

                    foreach($productos_carrito as $p){        

                        $precio_total += ($p['precio'] * $p['cantidad']) + $p['costo_envio']; //Va realizando la suma de todos los productos en el carrito para obtener el precio total

                        //Se obtienen los datos de la tienda a la que pertenece el producto, para posteriormente verificar que la tienda tiene permitido vender productos
                        $tienda = $this->MarketPlace_model->get_tienda($p['Usuarios_id']); 

                        //Si se encuentra una tienda cone stado Inactivo, no se puede concretar la compra, ya que la tienda no tiene permiso de venta
                        if ($tienda['estado'] == 'Inactivo') {
                            $tienda_inhabilitada = $tienda;
                            break;
                        }
                    }

                    if ($tienda_inhabilitada == FALSE) { //Si no se encuentran tienda inhabilitadas

                        $tarjeta = $this->MarketPlace_model->get_numero_tarjeta($this->input->post('txt_num_tarjeta')); //Obtiene los datos de la tarjeta

                        if ( $tarjeta['saldo'] >= $precio_total) { //Verifica que el saldo de la tarjeta alcance para realizar el pago de los productos
                            
                            $cantidad_suficiente = TRUE; //Varible para identificar si hay suficientes productos en inventario para realizar la venta

                            //Recorre los productos del carrito verificando si hay suficientes unidades en inventario como para satisfacer los que requiere el cliente
                            foreach($productos_carrito as $p) {  
                                if ($p['disponibles'] < $p['cantidad']){
                                    $cantidad_suficiente = FALSE;
                                }
                            }    
                            
                            if ($cantidad_suficiente == TRUE) { //Si hay suficientes productos para satisfacer la venta
                                
                                //Establece los datos correspondientes a la venta a realizar
                                $data_venta = array(
                                    'fecha' => mdate($format),
                                    'venta_total' => $precio_total,
                                    'Formas_Pago_id' => $tarjeta['idFormas_Pago'],
                                    'Direcciones_id' => $this->input->post('txt_direccion'),
                                );
            
                                $venta_id = $this->MarketPlace_model->add_venta($data_venta);  //Guarda los datos en la tabla de ventas y retorna el id de la venta creada
            
                                foreach($productos_carrito as $p) { //Recorre los productos que se encuentran en el carrito
                                    
                                    //Actualiza las unidades disponibles, restando la cantidad de productos vendidos
                                    $data_producto = array(
                                        'disponibles' => $p['disponibles'] - $p['cantidad'],
                                    ); 
                                    $this->MarketPlace_model->update_producto($p['idProductos'],$data_producto); 
                                    
                                    //Se registra la venta del producto
                                    $data_venta_producto = array(
                                        'cantidad' => $p['cantidad'],
                                        'Ventas_id' => $venta_id,
                                        'Productos_id' => $p['idProductos'],
                                    );  
                                    $this->MarketPlace_model->add_venta_producto($data_venta_producto); 
                                    
                                    //Se actualiza el saldo en la tarjeta del usuario, restando el precio del producto
                                    $data_metodo_pago = array(
                                        'saldo' => $tarjeta['saldo'] - $precio_total,                            
                                    ); 
                                    $this->MarketPlace_model->update_metodo_pago($tarjeta['idFormas_Pago'], $data_metodo_pago);
                                    
                                    //Se eliminan los productos del carrito
                                    $data_carrito = array(
                                        'idCarritos' => $p['idCarritos'],                            
                                    );                                     
                                    $this->MarketPlace_model->eliminar_carrito($data_carrito);
                                }
                                
                                //Abre el reporte de factura que se genera al realizar la compra
                                redirect('Reporte/ReporteFactura/'. $venta_id);

                                $this->session->set_flashdata('success', "Compra del producto realizada con exito.");    

                            } else { //Si no hay suficientes productos a la venta
                                $this->session->set_flashdata('error', "No hay suficientes productos a la venta."); 
                            }                                            
                        } else { //Si no se cuenta con suficiente saldo en la cuenta para realizar la transaccion
                            $this->session->set_flashdata('error', "No cuenta con saldo suficiente en la tarjeta para realizar la transacción.");     
                        }
                    } else { //Si uno de los productos que se van a comprar pertence a una tienda inhabilitada
                        $this->session->set_flashdata('error', "La tienda: " . $tienda_inhabilitada['nombre'] . " no tiene permitido vender productos.");     
                    }
                } else { //Si no existen productos en el carrito
                    $this->session->set_flashdata('error', "No hay productos en el carrito para comprar."); 
                }
            } else { //Si el codigo cvv ingresado no corresponde con el de la bd
                $this->session->set_flashdata('error', "El codigo CVV ingresado no concuerda con el numero de tarjeta."); 
            }
        } else { //Si no se ingresaron los datos necesarios para realizar el pago
            $this->session->set_flashdata('error', "Proporcione los parametros necesarios para ingresar el metodo de pago."); 
        }

        redirect('marketPlace/carrito/'.$this->session->userdata['logged_in']['users_id'],'refresh');
    }
}

