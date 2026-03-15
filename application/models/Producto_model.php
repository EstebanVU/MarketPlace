<?php
class Producto_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_all_categorias() // Obtiene todas las categorias
    {
        return $this->db->query("SELECT categorias.idCategorias, categorias.nombre
                                FROM categorias                                
                                ORDER BY categorias.idCategorias DESC")->result_array();
    }
    function get_categorias_producto($producto_id) //Obtiene las categorias del producto
    {
        return $this->db->query("SELECT categorias.idCategorias, categorias.nombre
                                FROM categorias
                                INNER JOIN productos_categorias ON productos_categorias.Categorias_id = categorias.idCategorias
                                INNER JOIN productos ON productos_categorias.Productos_id = productos.idProductos
                                WHERE productos.idProductos = $producto_id                               
                                ORDER BY categorias.idCategorias DESC")->result_array();
    }
    
    function delete_categoria_producto($params) //Elimina una categoria del producto
    {
        return $this->db->delete('productos_categorias', $params);                
    }

    function get_datos_producto($product_id) //Obtiene los datos del producto
    {
        return $this->db->query("SELECT productos.idProductos, productos.nombre, productos.descripcion, productos.disponibles, productos.fecha_publicacion, productos.ubicacion, productos.precio, productos.tiempo_envio, productos.costo_envio, productos.Usuarios_id
                                FROM productos
                                WHERE productos.idProductos = $product_id
                                ORDER BY productos.idProductos DESC")->row_array();
    }

    function get_fotos_producto($producto_id) //Obtiene las fotos de un unico producto
    {
        return $this->db->query("SELECT fotografias.idFotografias, fotografias.nombre, fotografias.descripcion, fotografias.Productos_id
                                FROM fotografias
                                WHERE fotografias.Productos_id = $producto_id
                                ORDER BY fotografias.idFotografias ASC")->result_array();
    }
    function verificar_categoria($nombre) //Verifica si la categoria ya existe
    {
        return $this->db->query("SELECT categorias.idCategorias
                                FROM categorias
                                WHERE categorias.nombre = '$nombre'")->row_array();
    }
    function verificar_categoria_producto($params) //Verifica si el producto tiene asignada esa categoria
    {
        return $this->db->query("SELECT idCategorias_Productos
                                FROM productos_categorias
                                WHERE productos_categorias.Productos_id = " . $params['Productos_id'] ." AND productos_categorias.Categorias_id = ". $params['Categorias_id'])->row_array();
    }

    function add_categoria($params) //Añade una nueva categoria
    {
        $this->db->insert('categorias', $params);
        return $this->db->insert_id();
    }
    function add_categoria_producto($params) //Le añade una categoria al producto
    {
        $this->db->insert('productos_categorias', $params);
        return $this->db->insert_id();
    }

    function add_producto($params) //Añade un nuevo producto
    {
        $this->db->insert('productos', $params);
        return $this->db->insert_id();
    }

    function update_producto($product_id, $params) //Actualiza un producto
    {
        $this->db->where('idProductos', $product_id);
        return $this->db->update('productos', $params);
    }
    
    function delete_producto($params) //Elimina un producto
    {
        return $this->db->delete('productos', $params);                
    }

    function add_foto($params) //Le añade una nueva foro al producto
    {
        $this->db->insert('fotografias', $params);
        return $this->db->insert_id();
    }

    function promedio_calificacion_producto($producto) //Saca el promedio de calificacion de un producto
    {
        return $this->db->query("SELECT avg(calificaciones.calificacion) as calificacionP
        FROM calificaciones
        WHERE calificaciones.Productos_id = " . $producto)->row_array();
    }

    function get_comentarios($id_producto){//Retorna los comentarios de un producto
        return $this->db->query("SELECT usuarios.nombre, comentarios.idComentarios , comentarios.descripcion, comentarios.Comentarios_id
        FROM comentarios 
        INNER JOIN usuarios ON usuarios.idUsuarios = comentarios.Usuarios_id
        INNER JOIN productos ON productos.idProductos = comentarios.Productos_id
        WHERE comentarios.Productos_id = $id_producto
        ORDER BY comentarios.idComentarios DESC")->result_array();
    }
    function get_comentarios_resp($id_comentario){//Retorna la respuesta de un comentario
        return $this->db->query("SELECT usuarios.nombre, comentarios.descripcion, comentarios.idComentarios
        FROM comentarios 
        INNER JOIN usuarios ON usuarios.idUsuarios = comentarios.Usuarios_id
        INNER JOIN productos ON productos.idProductos = comentarios.Productos_id
        WHERE comentarios.idComentarios = ( SELECT comentarios.Comentarios_id
                                            FROM comentarios 
                                            INNER JOIN usuarios ON usuarios.idUsuarios = comentarios.Usuarios_id
                                            INNER JOIN productos ON productos.idProductos = comentarios.Productos_id
                                            WHERE comentarios.idComentarios = $id_comentario)")->row_array();
    }
    function update_comentarios($id_comentario, $params) //Actualiza un comentario
    {
        $this->db->where('idComentarios', $id_comentario);
        return $this->db->update('comentarios', $params);
    }
}