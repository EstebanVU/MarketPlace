<?php 
class Reporte extends CI_Controller{
      function __construct() { 
            parent::__construct();
            $this->load->model('Reporte_model');
            $this->load->library('Pdf');
            $this->load->library('session');
      } 

      // Lista de los productos de todas las tiendas, filtrados por categoría, rango de 
      // fecha de publicación y precio menor a un rango que el comprador especifique.
     function ReporteProductos()
      {     
            $data['suscripciones'] = $this->Reporte_model->get_suscripciones($this->session->userdata['logged_in']['users_id']);

            $this->load->library('form_validation');
            $this->form_validation->set_rules('txt_rangoPrecio','Rango de fecha precio','required');
            $this->form_validation->set_rules('txt_rangoFecha1','Rango de fecha inicial','required');
            $this->form_validation->set_rules('txt_rangoFecha2','Rango de fecha final','required');

            if($this->form_validation->run() && $this->input->post('txt_categorias_id') != "0")
            {
                  $params = array(                
                        'categorias_id' => $this->input->post('txt_categorias_id'),
                        'rangoPrecio' => $this->input->post('txt_rangoPrecio'),
                        'rangoFecha1' => $this->input->post('txt_rangoFecha1'),
                        'rangoFecha2' => $this->input->post('txt_rangoFecha2'),                
                  );
                  
                  $data['producto'] = $this->Reporte_model->get_productos_baratos($params);
                  $data['_view'] = 'reports/Reporte_productos';
                  $this->load->view('layouts/main',$data);
            }
            else{
                  $this->session->set_flashdata('error', "Digite los parametros necesarios para generar el reporte.");
                  redirect('marketPlace/index');
            } 
      }

      // Información con la lista de todas las tiendas a las que me he suscrito y los productos 
      //respectivos que tengo añadidos en la lista de deseos en estas tiendas.
      function ReporteSuscripciones(){
            
            $data['suscripciones'] = $this->Reporte_model->get_suscripciones($this->session->userdata['logged_in']['users_id']);
            $data['_view'] = 'reports/Reporte_suscripciones';
            $this->load->view('layouts/main',$data);
      }

      //Información con la lista de todas las tiendas a las que me he suscrito y los productos 
      //respectivos que tengo añadidos en la lista de deseos en estas tiendas.
      function ReporteFactura($venta_id)
      {            
            $data['producto'] = $this->Reporte_model->get_facturaProductos($venta_id);
            $data['venta'] = $this->Reporte_model->get_facturaVenta($venta_id);
            $data['_view'] = 'reports/Reporte_factura';
            $this->load->view('layouts/main',$data);
      }

      //Cada comprador podrá ver sus productos comprados (datos completos) entre un rango de 
      //fechas cualquiera, con el saldo completo que se ha invertido y con cuál método de pago fue comprado.
      //Incluyendo un gráfico de barras con los productos en los que más dinero ha gastado
      function ReporteCompras(){

            $data['suscripciones'] = $this->Reporte_model->get_suscripciones($this->session->userdata['logged_in']['users_id']);
            $this->load->library('form_validation');
            $this->form_validation->set_rules('txt_rangoFecha1','Rango de fecha inicial','required');
            $this->form_validation->set_rules('txt_rangoFecha2','Rango de fecha final','required');
            
            if($this->form_validation->run())
            {
                  $params = array(                
                        'users_id' => $this->session->userdata['logged_in']['users_id'],
                        'rangoFecha1' => $this->input->post('txt_rangoFecha1'),
                        'rangoFecha2' => $this->input->post('txt_rangoFecha2'),                
                    );
                  
                  $data['compras'] = $this->Reporte_model->get_productos_comprados($params);
                  $data['_view'] = 'reports/Reporte_compras';
                  $this->load->view('layouts/main',$data);
            }
            else{
                  $this->session->set_flashdata('error', "Digite los parametros necesarios para generar el reporte.");
                  redirect('user/edit/'.$this->session->userdata['logged_in']['users_id']);
            }    
      }

      // Cada tienda podrá ver sus productos vendidos (datos completos) entre un rango de fechas 
      //cualquiera, con el saldo completo que se ha obtenido. Incluyendo un gráfico circular con los productos y 
      //el total de unidades vendidas
      function ReporteVentas() {
            
            $this->load->library('form_validation');
            $this->form_validation->set_rules('txt_rangoFecha1','Rango de fecha inicial','required');
            $this->form_validation->set_rules('txt_rangoFecha2','Rango de fecha final','required');
            
            if($this->form_validation->run())
            {  
                  $params = array(                
                        'users_id' => $this->session->userdata['logged_in']['users_id'],
                        'rangoFecha1' => $this->input->post('txt_rangoFecha1'),
                        'rangoFecha2' => $this->input->post('txt_rangoFecha2'),                
                        );
                  
                  $data['ventas'] = $this->Reporte_model->get_productos_ventas($params);
                  $data['_view'] = 'reports/Reporte_ventas';
                  $this->load->view('layouts/main',$data);
            }else{
                  $this->session->set_flashdata('error', "Digite los parametros necesarios para generar el reporte.");
                  redirect('tienda/index/'.$this->session->userdata['logged_in']['users_id']);
            } 
      }
}
?>