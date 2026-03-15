<?php
class Reporte_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_productos_baratos($params) // Obtiene los productos filtrados por rango de precio, fecha de publicación y categoría
    {
        return $this->db->query("SELECT productos.nombre, productos.precio, categorias.nombre as categoria, usuarios.nombre as tienda, productos.fecha_publicacion, productos.ubicacion
                                FROM productos 
                                INNER JOIN productos_categorias ON productos_categorias.Productos_id = productos.idProductos
                                INNER JOIN categorias ON categorias.idCategorias = productos_categorias.Categorias_id                       
                                INNER JOIN usuarios ON usuarios.idUsuarios = productos.Usuarios_id  

                                WHERE productos.precio <= " . $params['rangoPrecio'] ." AND productos.fecha_publicacion > '" . $params['rangoFecha1'] ."' AND 
                                productos.fecha_publicacion < '" . $params['rangoFecha2'] ."' AND productos_categorias.Categorias_id = " . $params['categorias_id'] ."
                                ORDER BY productos.precio ASC")->result_array();
    }

    
    function get_suscripciones($users_id) // Obtiene la información de las tiendas suscritas y los productos en asociados en la lista de deseos 
    {
        return $this->db->query("SELECT usuarios.nombre as tienda, usuarios.pais , usuarios.email, productos.nombre as producto, productos.precio
                                FROM usuarios
                                INNER JOIN suscripciones ON suscripciones.tienda_id = usuarios.idUsuarios
                                INNER JOIN productos ON productos.Usuarios_id = usuarios.idUsuarios
                                INNER JOIN productos_deseados ON productos_deseados.Productos_id = productos.idProductos
                                WHERE suscripciones.cliente_id = " . $users_id . " AND productos_deseados.Usuarios_id = " . $users_id . "")->result_array();
    }

    function get_facturaProductos($venta_id){ //Obtiene la inforacion de los productos que estan relacionados a la venta especificada

        return $this->db->query("SELECT productos.nombre, productos.descripcion , productos.precio , productos.costo_envio, ventas_productos.cantidad,
                                ((productos.precio * ventas_productos.cantidad) + productos.costo_envio) AS total
                                FROM productos
                                INNER JOIN ventas_productos ON ventas_productos.Productos_id = productos.idProductos
                                WHERE ventas_productos.Ventas_id = " . $venta_id . "")->result_array();
    }
    function get_facturaVenta($venta_id) { //Obtiene el detalle de la venta, dependiendo del id especificado

        return $this->db->query("SELECT ventas.idVentas, ventas.fecha , ventas.venta_total,
                                direcciones.pais, direcciones.provincia, direcciones.casillero, direcciones.postal, direcciones.observaciones,
                                formas_pago.titular_tarjeta, formas_pago.numero_tarjeta,
                                usuarios.nombre as usuario, usuarios.cedula
                                FROM ventas
                                INNER JOIN formas_pago ON formas_pago.idFormas_Pago = ventas.Formas_Pago_id
                                INNER JOIN usuarios ON usuarios.idUsuarios = formas_pago.Usuarios_id
                                INNER JOIN direcciones ON direcciones.idDirecciones = ventas.Direcciones_id
                                WHERE ventas.idVentas = " . $venta_id . "")->row_array();
    }

    function get_productos_comprados($params){ //Obtiene los productos comprados de un usuario entre un rando de fechas
        return $this->db->query("SELECT productos.nombre, productos.descripcion , productos.precio , productos.costo_envio, ventas_productos.cantidad, ((productos.precio * ventas_productos.cantidad) +productos.costo_envio) AS total,
        formas_pago.titular_tarjeta, formas_pago.numero_tarjeta
        FROM productos 
        INNER JOIN ventas_productos ON ventas_productos.Productos_id = productos.idProductos 
        INNER JOIN ventas ON ventas.idVentas = ventas_productos.Ventas_id 
        INNER JOIN formas_pago ON formas_pago.idFormas_Pago = ventas.Formas_Pago_id 
        WHERE formas_pago.Usuarios_id = " . $params['users_id'] ." AND ventas.fecha > '" . $params['rangoFecha1'] ."'  AND ventas.fecha < '" . $params['rangoFecha2'] ."' 
    ")->result_array();

    }

    function get_productos_ventas($params){ //Obtiene los productos de vendidos por la tienda especificada en un rango de fechas
        return $this->db->query("SELECT productos.nombre, productos.descripcion , productos.precio , productos.costo_envio,
                                ventas_productos.cantidad, ((productos.precio * ventas_productos.cantidad) + productos.costo_envio) AS total
                                FROM productos 
                                INNER JOIN ventas_productos ON ventas_productos.Productos_id = productos.idProductos 
                                INNER JOIN ventas ON ventas.idVentas = ventas_productos.Ventas_id 
                                WHERE productos.Usuarios_id = " . $params['users_id'] ." AND ventas.fecha > '" . $params['rangoFecha1'] ."' 
                                AND ventas.fecha < '" . $params['rangoFecha2'] ."' ")->result_array();
    }
}