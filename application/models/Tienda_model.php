<?php
class Tienda_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_all_categorias() //Obtiene todas la categorias
    {
        return $this->db->query("SELECT categorias.idCategorias, categorias.nombre
                                FROM categorias                                
                                ORDER BY categorias.idCategorias DESC")->result_array();
    }

    function get_productos_tienda($users_id) //Obtiene los productos de la tienda
    {
        return $this->db->query("SELECT productos.idProductos, productos.nombre, productos.descripcion, productos.disponibles, productos.fecha_publicacion, productos.ubicacion, productos.precio, productos.tiempo_envio, productos.costo_envio
                                FROM productos
                                INNER JOIN usuarios ON usuarios.idUsuarios = productos.Usuarios_id
                                WHERE productos.Usuarios_id = $users_id
                                ORDER BY usuarios.idUsuarios DESC")->result_array();
    }

    function get_datos_tienda($users_id) //Obtiene los datos de la tienda
    {
        return $this->db->query("SELECT usuarios.idUsuarios , usuarios.email, usuarios.password, usuarios.nombre, usuarios.foto_perfil, usuarios.telefono, usuarios.pais, usuarios.provincia, usuarios.cedula from usuarios WHERE usuarios.idUsuarios = " . $users_id)->row_array();
    }

    function get_redes_tienda($users_id) //Obtiene las redes sociales de la tienda
    {
        return $this->db->query("SELECT redes_sociales.nombre, redes_sociales_usuarios.url
                                FROM redes_sociales_usuarios 
                                INNER JOIN redes_sociales ON redes_sociales.idRedes_Sociales = redes_sociales_usuarios.Redes_Sociales_id
                                WHERE redes_sociales_usuarios.Usuarios_id = " . $users_id)->result_array();
    }
    function get_suscripciones($tienda_id) //Obtiene los usuarios que estén suscritos a la tienda
    {
        return $this->db->query("SELECT usuarios.idUsuarios , usuarios.email, usuarios.password, usuarios.nombre, usuarios.foto_perfil, usuarios.telefono, usuarios.pais, usuarios.provincia, usuarios.cedula
                                FROM usuarios 
                                INNER JOIN suscripciones ON suscripciones.cliente_id = usuarios.idUsuarios
                                WHERE suscripciones.tienda_id = " . $tienda_id)->result_array();
    }
    function get_usuarios_deseos($tienda_id) //Obtiene usuarios que tengan productos de la tienda en la lista de deseos
    {
        return $this->db->query("SELECT DISTINCT usuarios.idUsuarios , usuarios.email, usuarios.password, usuarios.nombre, usuarios.foto_perfil, usuarios.telefono, usuarios.pais, usuarios.provincia, usuarios.cedula
                                FROM usuarios 
                                INNER JOIN productos_deseados ON productos_deseados.Usuarios_id = usuarios.idUsuarios
                                INNER JOIN productos ON productos.idProductos = productos_deseados.Productos_id
                                WHERE productos.Usuarios_id = " . $tienda_id)->result_array();
    }
    function get_all_fotos_productos() //Obtiene todas las fotos de los productos
    {
        return $this->db->query("SELECT fotografias.idFotografias, fotografias.nombre, fotografias.descripcion, fotografias.Productos_id
                                FROM fotografias
                                ORDER BY fotografias.idFotografias ASC")->result_array();
    } 
    function verificar_usuario_suscrito($params) //Verifica si el usuario ya se encuentra suscrito a la tienda
    {
        return $this->db->query("SELECT suscripciones.IdSuscripciones
                                FROM suscripciones
                                WHERE suscripciones.cliente_id = " . $params['cliente_id'] . " AND suscripciones.tienda_id = " . $params['tienda_id'])->row_array();
    } 

    function verifica_denuncia($params) //Verifica si el usuario ya se encuentra suscrito a la tienda
    {
        return $this->db->query("SELECT denuncias.idDenuncias
                                FROM denuncias
                                WHERE denuncias.cliente_id = " . $params['cliente_id'] . " AND denuncias.tienda_id = " . $params['tienda_id'])->row_array();
    } 
    function verifica_cantidad_denuncias($tienda) //Verifica si el usuario ya se encuentra suscrito a la tienda
    {
        return $this->db->query("SELECT COUNT(denuncias.idDenuncias)
                                FROM denuncias
                                WHERE denuncias.tienda_id = " . $tienda)->row_array();
    } 

    function buscar_productos_categoria($tienda,$producto,$categoria){ //Busca productos según su categoria
        return $this->db->query("SELECT DISTINCT productos.idProductos, productos.nombre, productos.descripcion, productos.disponibles, productos.fecha_publicacion, productos.ubicacion, productos.precio, productos.tiempo_envio, productos.costo_envio, productos.Usuarios_id
                                    FROM productos
                                    INNER JOIN usuarios ON usuarios.idUsuarios = productos.Usuarios_id 
                                    INNER JOIN productos_categorias ON productos_categorias.Productos_id = productos.idProductos
                                    WHERE usuarios.idUsuarios = ". $tienda ." AND usuarios.estado = 'Activo' AND productos_categorias.Categorias_id = " . $categoria['idCategorias'] . " AND productos.nombre LIKE '%" . $producto . "%'
                                    ORDER BY usuarios.nombre ASC")->result_array();
    }

    function buscar_productos($tienda,$producto){ //Busca una lista de productos a partir del nombre ingresado
        return $this->db->query("SELECT productos.idProductos, productos.nombre, productos.descripcion, productos.disponibles, productos.fecha_publicacion, productos.ubicacion, productos.precio, productos.tiempo_envio, productos.costo_envio, productos.Usuarios_id
                                FROM productos
                                INNER JOIN usuarios ON usuarios.idUsuarios = productos.Usuarios_id
                                WHERE usuarios.idUsuarios = ". $tienda ." AND usuarios.estado = 'Activo' AND productos.nombre LIKE '%" . $producto . "%'
                                ORDER BY productos.nombre ASC")->result_array();

    }
    //Actualiza el estado de la tienda a inactivo, prohibiendole vender productos
    function deshabilita_tienda($tienda,$params){
        $this->db->where('idUsuarios', $tienda);
        return $this->db->update('usuarios', $params);
    }

    function add_denuncia($params) //Añade una nueva suscripcion
    {
        $this->db->insert('denuncias', $params);
        return $this->db->insert_id();
    }
    function delete_denuncia($params) //Elimina una suscripcion
    {
        return $this->db->delete('denuncias', $params);                
    } 

    function add_suscripcion($params) //Añade una nueva suscripcion
    {
        $this->db->insert('suscripciones', $params);
        return $this->db->insert_id();
    }
    function delete_suscripcion($params) //Elimina una suscripcion
    {
        return $this->db->delete('suscripciones', $params);                
    }    

    function promedio_calificacion_tienda($tienda) //Saca el promedio de calificacion de la tienda a partir del promedio de sus productos
    {
        return $this->db->query("SELECT avg(calificaciones.calificacion) as calificacionT
        FROM calificaciones INNER JOIN productos ON productos.idProductos = calificaciones.Productos_id 
        INNER JOIN usuarios ON usuarios.idUsuarios = productos.Usuarios_id 
        WHERE usuarios.idUsuarios =  " . $tienda)->row_array();
    }
}