<?php
class User_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_user($users_id) //Obtiene los datos de un usuario según su id
    {
        return $this->db->query("SELECT usuarios.idUsuarios , usuarios.email, usuarios.password, usuarios.nombre, usuarios.foto_perfil, usuarios.telefono, usuarios.pais, usuarios.provincia, usuarios.cedula, usuarios.tipo from usuarios WHERE usuarios.idUsuarios = " . $users_id)->row_array();
    }

    function add_user($params) //Añade un nuevo usuario
    {
        $this->db->insert('usuarios', $params);
        return $this->db->insert_id();
    }

    function add_red_social($params) //Asigna una nueva red social al usuario
    {
        $this->db->insert('redes_sociales_usuarios', $params);
        return $this->db->insert_id();
    }

    function add_direccion_envio($params) //Asigna una nueva dirección de envío al usuario
    {
        $this->db->insert('direcciones', $params);
        return $this->db->insert_id();
    }
    function add_metodo_pago($params) //Asigna un nuevo metodo de pago al usuario
    {
        $this->db->insert('formas_pago', $params);
        return $this->db->insert_id();
    }

    function update_user($users_id, $params) //Actualiza los datos de un usuario
    {
        $this->db->where('idUsuarios', $users_id);
        return $this->db->update('usuarios', $params);
    }

    function delete_user($users_id) //Elimina un usuario segun su id
    {
        return $this->db->delete('usuarios', array('idUsuarios' => $users_id));
    }
    function delete_red($red_id) //Elimina una rede social segun su id
    {
        return $this->db->delete('redes_sociales_usuarios', array('idRedesSociales_Usuarios' => $red_id));
    }
    function update_metodo_pago($metodo_id,$params) //Elimina un metodo de pago segun su id
    {
        $this->db->where('idFormas_Pago', $metodo_id);
        return $this->db->update('formas_pago', $params);
    }
    function update_direccion($direccion_id,$params) //Elimina un metodo de pago segun su id
    {
        $this->db->where('idDirecciones', $direccion_id);
        return $this->db->update('direcciones', $params);
    }

    function get_metodos_pago_usuario($usuario_id){ //Obtiene todos los metodos de pago del usuario especificado
        return $this->db->query("SELECT formas_pago.idFormas_Pago, formas_pago.titular_tarjeta, formas_pago.numero_tarjeta, formas_pago.codigo_cvv, formas_pago.saldo, formas_pago.vencimiento, formas_pago.Usuarios_id
                                FROM formas_pago                                 
                                WHERE formas_pago.Usuarios_id = " . $usuario_id . " AND formas_pago.estado = 'Activo' ")->result_array();
    }
    function get_metodo_pago($metodo_id){ //Obtienen un metodo de pago dependiendo del id
        return $this->db->query("SELECT formas_pago.idFormas_Pago, formas_pago.titular_tarjeta, formas_pago.numero_tarjeta, formas_pago.codigo_cvv, formas_pago.saldo, formas_pago.vencimiento, formas_pago.Usuarios_id
                                FROM formas_pago                                 
                                WHERE formas_pago.idFormas_Pago = " . $metodo_id)->row_array();
    }
    function get_numero_tarjeta($num_tarjeta){ //Obtiene un metodo de pago dependiendo del numero de tarjeta
        return $this->db->query("SELECT formas_pago.idFormas_Pago, formas_pago.titular_tarjeta, formas_pago.numero_tarjeta, formas_pago.codigo_cvv, formas_pago.saldo, formas_pago.vencimiento, formas_pago.Usuarios_id
                                FROM formas_pago                                 
                                WHERE formas_pago.numero_tarjeta = " . $num_tarjeta)->row_array();
    }
    function get_direcciones_usuario($usuario_id){ //OBtiene todas las direcciones de envio del usuario especificado
        return $this->db->query("SELECT direcciones.idDirecciones, direcciones.pais, direcciones.provincia, direcciones.casillero, direcciones.postal, direcciones.observaciones, direcciones.Usuarios_id
                                FROM direcciones                                 
                                WHERE direcciones.Usuarios_id = " . $usuario_id . " AND direcciones.estado = 'Activo'")->result_array();
    }
    function get_direccion($direccion_id) { //Obtiene una direccion de envio dependiendo del id
        return $this->db->query("SELECT direcciones.idDirecciones, direcciones.pais, direcciones.provincia, direcciones.casillero, direcciones.postal, direcciones.observaciones, direcciones.Usuarios_id
                                FROM direcciones                                 
                                WHERE direcciones.idDirecciones = " . $direccion_id)->row_array();
    }
    function get_redes_usuario($usuario_id){ //Obtiene las redes sociales del usuario, tomando en cuenta la tabla redes_sociales_usuarios y redes_sociales
        return $this->db->query("SELECT redes_sociales.idRedes_Sociales, redes_sociales.nombre, redes_sociales_usuarios.url, redes_sociales_usuarios.idRedesSociales_Usuarios, redes_sociales_usuarios.Usuarios_id
                                FROM redes_sociales_usuarios 
                                INNER JOIN redes_sociales ON redes_sociales.idRedes_Sociales = redes_sociales_usuarios.Redes_Sociales_id
                                WHERE redes_sociales_usuarios.Usuarios_id = " . $usuario_id)->result_array();
    }
    function get_redes_sociales_usuario($red_id){ //Obtiene los datos directamente de la tabla redes_sociales_usuarios
        return $this->db->query("SELECT redes_sociales_usuarios.idRedesSociales_Usuarios, redes_sociales_usuarios.Usuarios_id
                                FROM redes_sociales_usuarios 
                                WHERE redes_sociales_usuarios.idRedesSociales_Usuarios = " . $red_id)->row_array();
    }
}
