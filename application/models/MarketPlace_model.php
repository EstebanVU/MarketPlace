<?php
class MarketPlace_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_productos_destacados() //Obtiene los 10 productos mas vendidos
    {
        return $this->db->query("SELECT Productos_id, count(Productos_id) AS vendido, productos.idProductos, productos.nombre, productos.descripcion, productos.disponibles, productos.fecha_publicacion, productos.ubicacion, productos.precio, productos.tiempo_envio, productos.costo_envio, productos.Usuarios_id
                                FROM ventas_productos
                                INNER JOIN productos ON productos.idProductos = ventas_productos.Productos_id
                                GROUP BY Productos_id 
                                Having Count(*) >= 1 order by 2 desc LIMIT 10")->result_array();
    }
    function get_all_tiendas() //Obtiene todas las tiendas
    {
        return $this->db->query("SELECT usuarios.idUsuarios, usuarios.nombre, usuarios.foto_perfil
                                FROM usuarios
                                WHERE usuarios.tipo = 'Tienda' AND usuarios.estado = 'Activo'
                                ORDER BY usuarios.nombre ASC")->result_array();
    }
    function get_all_categorias() //Obtiene todas las categorias
    {
        return $this->db->query("SELECT categorias.idCategorias, categorias.nombre
                                FROM categorias                                
                                ORDER BY categorias.idCategorias DESC")->result_array();
    }
    function get_categoria($nombre) //Obtiene el id de una categoria a partir del nombre
    {
        return $this->db->query("SELECT categorias.idCategorias
                                FROM categorias
                                WHERE categorias.nombre = '$nombre'")->row_array();
    }
    function get_all_fotos_productos() //Obtiene todas las fotos de los productos
    {
        return $this->db->query("SELECT fotografias.idFotografias, fotografias.nombre, fotografias.descripcion, fotografias.Productos_id
                                FROM fotografias
                                ORDER BY fotografias.idFotografias ASC")->result_array();
    } 
    function get_tienda($tienda_id) //Obtiene los datos de un usuario según su id
    {
        return $this->db->query("SELECT usuarios.idUsuarios , usuarios.email, usuarios.password, usuarios.nombre, usuarios.foto_perfil, usuarios.telefono, usuarios.pais, usuarios.provincia, usuarios.cedula , usuarios.estado
                                FROM usuarios WHERE usuarios.idUsuarios = " . $tienda_id)->row_array();
    }
    function buscar_tienda_producto($producto){ //Busca una tienda según un producto
        return $this->db->query("SELECT DISTINCT usuarios.idUsuarios, usuarios.nombre, usuarios.foto_perfil
                                FROM usuarios
                                INNER JOIN productos ON productos.Usuarios_id = usuarios.idUsuarios
                                WHERE usuarios.tipo = 'Tienda'AND usuarios.estado = 'Activo' AND productos.idProductos = " . $producto['idProductos'] . "
                                ORDER BY usuarios.nombre ASC")->result_array();
    }

    function buscar_tiendas($tienda){ //Busca una lista de tiendas que coincidan en el nombre
        return $this->db->query("SELECT usuarios.idUsuarios, usuarios.nombre, usuarios.foto_perfil
                                FROM usuarios
                                WHERE usuarios.tipo = 'Tienda' AND usuarios.estado = 'Activo' AND usuarios.nombre LIKE '%" . $tienda . "%'
                                ORDER BY usuarios.nombre ASC")->result_array();
    }
    function buscar_tiendas_categoria($tienda,$categoria){ //Busca una tienda por categoria, tomando la categoria de sus productos
        return $this->db->query("SELECT DISTINCT  usuarios.idUsuarios, usuarios.nombre, usuarios.foto_perfil
                                    FROM usuarios
                                    INNER JOIN productos ON productos.Usuarios_id = usuarios.idUsuarios
                                    INNER JOIN productos_categorias ON productos_categorias.Productos_id = productos.idProductos
                                    WHERE usuarios.tipo = 'Tienda' AND usuarios.estado = 'Activo' AND productos_categorias.Categorias_id = " . $categoria['idCategorias'] . " AND usuarios.nombre LIKE '%" . $tienda . "%'
                                    ORDER BY usuarios.nombre ASC")->result_array();
    }

    function buscar_productos($producto){ //Busca una lista de productos a partir del nombre ingresado
        return $this->db->query("SELECT productos.idProductos, productos.nombre, productos.descripcion, productos.disponibles, productos.fecha_publicacion, productos.ubicacion, productos.precio, productos.tiempo_envio, productos.costo_envio, productos.Usuarios_id
                                FROM productos
                                INNER JOIN usuarios ON usuarios.idUsuarios = productos.Usuarios_id
                                WHERE usuarios.estado = 'Activo' AND productos.nombre LIKE '%" . $producto . "%'
                                ORDER BY productos.nombre ASC")->result_array();

    }
    function buscar_productos_categoria($producto,$categoria){ //Busca productos según su categoria
        return $this->db->query("SELECT DISTINCT productos.idProductos, productos.nombre, productos.descripcion, productos.disponibles, productos.fecha_publicacion, productos.ubicacion, productos.precio, productos.tiempo_envio, productos.costo_envio, productos.Usuarios_id
                                    FROM productos
                                    INNER JOIN usuarios ON usuarios.idUsuarios = productos.Usuarios_id 
                                    INNER JOIN productos_categorias ON productos_categorias.Productos_id = productos.idProductos
                                    WHERE usuarios.tipo = 'Tienda' AND usuarios.estado = 'Activo' AND productos_categorias.Categorias_id = " . $categoria['idCategorias'] . " AND productos.nombre LIKE '%" . $producto . "%'
                                    ORDER BY usuarios.nombre ASC")->result_array();
    }
        
    function buscar_productos_tienda($tienda){ //Busca los productos de una tienda, según el id de la tienda
        return $this->db->query("SELECT productos.idProductos, productos.nombre, productos.descripcion, productos.disponibles, productos.fecha_publicacion, productos.ubicacion, productos.precio, productos.tiempo_envio, productos.costo_envio, productos.Usuarios_id
                                FROM productos
                                INNER JOIN usuarios ON usuarios.idUsuarios = productos.Usuarios_id
                                WHERE usuarios.estado = 'Activo' AND productos.Usuarios_id = " . $tienda['idUsuarios'] . "
                                ORDER BY productos.idProductos DESC")->result_array();
    }
    function buscar_productos_tienda_categoria($tienda,$categoria){ //Busca un producto dependiendo de la tienda y la categoria
        return $this->db->query("SELECT productos.idProductos, productos.nombre, productos.descripcion, productos.disponibles, productos.fecha_publicacion, productos.ubicacion, productos.precio, productos.tiempo_envio, productos.costo_envio, productos.Usuarios_id
                                FROM productos
                                INNER JOIN productos_categorias ON productos_categorias.Productos_id = productos.idProductos
                                INNER JOIN usuarios ON usuarios.idUsuarios = productos.Usuarios_id
                                WHERE usuarios.estado = 'Activo' AND productos.Usuarios_id = " . $tienda['idUsuarios'] . " AND productos_categorias.Categorias_id = " . $categoria['idCategorias'] . "
                                ORDER BY productos.idProductos DESC")->result_array();
    }
    function buscar_tienda_producto_categoria($producto,$categoria){ //busca una tienda dependiendo del producto y la categoria
        return $this->db->query("SELECT DISTINCT  usuarios.idUsuarios, usuarios.nombre, usuarios.foto_perfil
                                    FROM usuarios
                                    INNER JOIN productos ON productos.Usuarios_id = usuarios.idUsuarios
                                    INNER JOIN productos_categorias ON productos_categorias.Productos_id = productos.idProductos
                                    WHERE usuarios.tipo = 'Tienda' AND usuarios.estado = 'Activo' AND productos.idProductos = " . $producto['idProductos'] . " AND productos_categorias.Categorias_id = " . $categoria['idCategorias'] . "
                                    ORDER BY usuarios.idUsuarios DESC")->result_array();                                    
    }
    //Carrito
    function add_carrito($params) //Añade un nuevo articulo al carrito del usuario
    {
        $this->db->insert('carritos', $params);
        return $this->db->insert_id();
    }
    function cantidad_producto($product_id) //Obtiene la cantidad de productos que están disponibles a partir del ids
    {
        return $this->db->query("SELECT productos.disponibles
                                FROM productos
                                WHERE productos.idProductos = $product_id")->row_array();
    }
    function verificar_producto_carrito($params) //Verifica si un producto ya se encuentra en el carrito del usuario
    {
        return $this->db->query("SELECT carritos.cantidad, carritos.idCarritos
                                FROM carritos
                                WHERE carritos.Usuarios_id = " . $params['Usuarios_id'] ." AND carritos.Productos_id = " . $params['Productos_id'])->row_array();
    }
    function update_carrito($carrito_id, $params) //Actualiza un producto del carrito de un usuario
    {
        $this->db->where('idCarritos', $carrito_id);
        return $this->db->update('carritos', $params);
    }
    function eliminar_carrito($params) //Elimina un producto del carrito
    {
        return $this->db->delete('carritos', $params);                
    }
    function get_carrito($user_id) //Obtiene todos lo productos que el usuario tiene en el carrito
    {
        return $this->db->query("SELECT productos.idProductos, productos.nombre, productos.descripcion, productos.disponibles, productos.fecha_publicacion, productos.ubicacion, productos.precio, productos.tiempo_envio, productos.costo_envio, productos.Usuarios_id, carritos.cantidad, carritos.idCarritos
                                FROM productos
                                INNER JOIN carritos ON carritos.Productos_id = productos.idProductos
                                WHERE productos.idProductos = carritos.Productos_id AND carritos.Usuarios_id = $user_id
                                ORDER BY productos.idProductos DESC")->result_array();
    }
    //Lista de deseos
    function verificar_producto_deseo($params) //Verifica si el producto ya se encuentra en la lista de deseos del usuario
    {
        return $this->db->query("SELECT productos_deseados.idProductos_Deseados
                                FROM productos_deseados
                                WHERE productos_deseados.Usuarios_id = " . $params['Usuarios_id'] ." AND productos_deseados.Productos_id = " . $params['Productos_id'])->row_array();
    }
    function add_deseo($params) //Añade un nuevo producto a la lista de deseos del usuario
    {
        $this->db->insert('productos_deseados', $params);
        return $this->db->insert_id();
    }
    function add_venta($params) //Añade un nuevo producto a la lista de deseos del usuario
    {
        $this->db->insert('ventas', $params);
        return $this->db->insert_id();
    }
    function add_venta_producto($params) //Añade un nuevo producto a la lista de deseos del usuario
    {
        $this->db->insert('ventas_productos', $params);
        return $this->db->insert_id();
    }
    function eliminar_deseo($params) //Elimina un producto de la lista de deseos del usuario
    {
        return $this->db->delete('productos_deseados', $params);                
    }
    function get_deseos($user_id) //Obtiene todos los productos de la lista de deseos del usuario
    {
        return $this->db->query("SELECT productos.idProductos, productos.nombre, productos.descripcion, productos.disponibles, productos.fecha_publicacion, productos.ubicacion, productos.precio, productos.tiempo_envio, productos.costo_envio, productos.Usuarios_id
                                FROM productos
                                INNER JOIN productos_deseados ON productos_deseados.Productos_id = productos.idProductos
                                WHERE productos.idProductos = productos_deseados.Productos_id AND productos_deseados.Usuarios_id = $user_id
                                ORDER BY productos.idProductos DESC")->result_array();
    }

    function verificar_tarjeta($data) //Verifica que la tarjeta exista y que el cvv corresponda al de la bd
    {
        $tarjeta_existe = $this->get_tarjeta_existente($data['numero_tarjeta']);

		//Se compara el cvv que viene por POST con el encriptado de la BD por medio de password_verify()
		if ($tarjeta_existe != false && password_verify($data['codigo_cvv'], $tarjeta_existe[0]->codigo_cvv)) {
			return true; //Existe: autenticado
		} else {
			return false; //No autenticado
		}
    }

    function get_tarjeta_existente($num_tarjeta){ //Verifica que la tarjeta exista
        $query = $this->db->query("SELECT formas_pago.idFormas_Pago, formas_pago.titular_tarjeta, formas_pago.numero_tarjeta, formas_pago.codigo_cvv, formas_pago.saldo, formas_pago.vencimiento, formas_pago.Usuarios_id
                                FROM formas_pago                                 
                                WHERE formas_pago.numero_tarjeta = " . $num_tarjeta);
                            
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }
    function get_numero_tarjeta($num_tarjeta){ //Obtiene un metodo de pago dependiendo del numero de tarjeta
        return $this->db->query("SELECT formas_pago.idFormas_Pago, formas_pago.titular_tarjeta, formas_pago.numero_tarjeta, formas_pago.codigo_cvv, formas_pago.saldo, formas_pago.vencimiento, formas_pago.Usuarios_id
                                FROM formas_pago                                 
                                WHERE formas_pago.numero_tarjeta = " . $num_tarjeta)->row_array();
    }
    function update_producto($product_id, $params) //Actualiza un producto
    {
        $this->db->where('idProductos', $product_id);
        return $this->db->update('productos', $params);
    }
    function update_metodo_pago($metodo_id, $params) //Actualiza un producto
    {
        $this->db->where('idFormas_Pago', $metodo_id);
        return $this->db->update('formas_pago', $params);
    }

    function add_calificacion($params) //Añade una calificacion
    {
        $this->db->insert('calificaciones', $params);
        return $this->db->insert_id();
    }

    function verificar_calificacion($params) //Verifica si ya se ha dado la calificacion
    {
        return $this->db->query("SELECT calificaciones.idCalificaciones
                                FROM calificaciones
                                WHERE calificaciones.Usuarios_id = " . $params['Usuarios_id'] ." AND calificaciones.Productos_id = " . $params['Productos_id'])->row_array();
    }

    function update_calificacion($calificacion_id, $params) //Actualiza un producto
    {
        $this->db->where('idCalificaciones', $calificacion_id);
        return $this->db->update('calificaciones', $params);
    }

    function add_comentario($params) //Añade un nuevo comentario al producto
    {
        $this->db->insert('comentarios', $params);
        return $this->db->insert_id();
    }
}
