<?php if (!empty($this->session)) { 
		if($this->session->flashdata('error')){ echo "<div class='msg_box_user error' >" .  $this->session->flashdata('error') . "</div>"; } 
		if($this->session->flashdata('success')){ echo "<div class='msg_box_user success' >" .  $this->session->flashdata('success') . "</div>"; } 
} ?>
<header>
    <div id="main_header" class="container-fluid">
        <div class="row">
            <div class="col">
                <a href="<?php echo site_url('marketPlace/index'); ?>" >
                    <?php
                        echo "<img class='logo' src='" . site_url('resources/img/logo1.svg') . "' alt='Foto de perfil' title='Foto de perfil' />";
                    ?>
                </a>
            </div>
            
            <div class="input-group col busqueda" >
                <div class="input-group-prepend">
                    <div class="input-group-text" id="btnGroupAddon">
                        <select class="form-select form-select-sm" aria-label=".form-select-sm example">
                            <option selected>Categorias</option>
                            <?php foreach ($categorias as $c) { 
                                echo "<option value=". $c['nombre'] .">". $c['nombre'] ." </option>";
                             } ?>
                        </select>
                    </div>
                </div>

                <input type="text" class="form-control" name="txt_buscar" id="txt_buscar" placeholder="Buscar" title="Buscar">   
            </div>

            <?php if (isset($this->session->userdata['logged_in']['logged_in']) && $this->session->userdata['logged_in']['logged_in'] == TRUE) { ?>

				<div id="perfil_bar" class="col">
					<?php 
						echo "<input class='perfil' type='image' src='" . site_url('resources/img/icon_bandeja_entrada.svg') . "' alt='Bandeja de entrada' title='Bandeja de entrada' width=50 />";
					?> 

                    <?php 
						echo "<input class='perfil' type='image' src='" . site_url('resources/img/icon_reporte.svg') . "' alt='Reportes' title='Reportes' width=50 data-bs-toggle='modal' data-bs-target='#reportes_modal'/>";                            
					?>  

					<?php 
						echo "<input class='perfil' type='image' src='" . site_url('resources/img/carrito.svg') . "' alt='Carrito de compras' title='Carrito de compras'  width=50 />";
					?>  

					<div class="perfil">
                        <a href="<?php echo site_url('user/edit/' . $this->session->userdata['logged_in']['users_id']); ?>" >
                            <?php
                                echo "<img class='img_perfil' src='" . site_url('resources/photos/' . $this->session->userdata['logged_in']['photo']) . "' alt='Foto de perfil' title='Foto de perfil' width=70/>";
                            ?>
                        </a>
						<br>
						<?php echo "<label class='label label-primary lbl_user'>" . $this->session->userdata['logged_in']['realname'] . "</label>"; ?>
					</div>
				</div>

			<?php } else { ?>

				<div id="sesion" class="col">
					<a href="<?php echo site_url('auth/login'); ?>" class="lbl_links">Iniciar sesión</a>
					<label class="lbl_links">/</label>
					<a href="<?php echo site_url('user/add'); ?>" class="lbl_links">Registrarse</a>
				</div>

			<?php } ?>

        </div>
    </div>

</header>

<body>
    <h1 style="margin: 0px 20px 20px 20px">Carrito de compras</h1>
        
    <div class="container-fluid product_box">
        <div class="row ">

            <?php foreach ($productos_carrito as $p) {                          
                $foto = '';
                foreach ($fotos as $f) { 
                    if($f['Productos_id'] == $p['idProductos']){
                        $foto = $f['nombre'];
                        break;
                    }
                } ?>     

                <div class="col">
                    <a href="<?php echo site_url('producto/index/' . $p['idProductos']); ?>" >
                        <?php 
                            echo "<input class='logo_pt' type='image' src='" . site_url('resources/photos/products/' . $foto) . "' alt='Logo' max-width=270px height=200px/>";
                            echo"<br>";
                            echo "<label>" . $p['nombre'] . "</label><br>";
                            echo "<label>Precio: $" . number_format($p['precio']) . " x " . $p['cantidad'] . "</label><br>";                            
                            echo "<label>Envio: $" . number_format($p['costo_envio']) . "</label>";                            
                        ?>   
                    </a>
                    <br>
                    <?php echo form_open('marketPlace/eliminar_carrito/' . $p['idProductos']);?>
                        <button type="submit" class="cajatexto">Eliminar</button>
                    <?php echo form_close(); ?>                    
                </div>
            <?php } ?>
        </div>
        <div class="row">    
            <h3>Total: $<?php echo number_format($precio_total)?></h3>
        </div>
  
            
        <button type="button" class="cajatexto" data-bs-toggle="modal" data-bs-target="#compra_modal" id="btn_compra_modal">Comprar productos del carrito</button> <!--Activa la ventana flotante-->          
        

    </div>
    <h1 style="margin: 20px 20px 20px 20px">Lista de deseos</h1>
        
    <div class="container-fluid">
        <div class="row product_box">

            <?php foreach ($productos_deseos as $p) {                          
                $foto = '';
                foreach ($fotos as $f) { 
                    if($f['Productos_id'] == $p['idProductos']){
                        $foto = $f['nombre'];
                        break;
                    }
                } ?>     

                <div class="col">
                    <a href="<?php echo site_url('producto/index/' . $p['idProductos']); ?>" >
                        <?php 
                            echo "<input class='logo_pt' type='image' src='" . site_url('resources/photos/products/' . $foto) . "' alt='Logo' max-width=270px height=200px/>";
                            echo"<br>";
                            echo "<label>" . $p['nombre'] . "</label><br>";
                            echo "<label>Precio: $" . number_format($p['precio']) . "</label><br>";                            
                            echo "<label>Envio: $" . number_format($p['costo_envio']) . "</label>";
                        ?>   
                    </a>
                    <br>
                    <?php echo form_open_multipart('marketPlace/eliminar_deseo/' . $p['idProductos']);?>
                        <button type="submit" class="cajatexto" style="margin-bottom: 30px;">Eliminar</button>
                    <?php echo form_close(); ?>   
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Ventana flotante para realizar el pago de los productos -->
    <div class="modal fade" id="compra_modal" tabindex="-1" aria-labelledby="compra_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="compra_modalLabel">Compra de los productos</h4>                
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php echo form_open('marketPlace/comprar_carrito/' . $p['idProductos']);?>
                <div class="modal-body">                
                    <div class="row">                    
                        <label for="">Dirección de envío: </label>
                        <select id="txt_direccion" name="txt_direccion" class="form-select-sm cajatexto" aria-label=".form-select-sm example" style="width: 50%; display: inline; margin: 10px 0px 10px 10px;">
                            <option selected value="0">Seleccione la dirección a utilizar</option>	
                            <?php foreach ($direcciones as $d) { ?>      
                                <option value="<?php echo $d['idDirecciones'] ?>"><?php echo $d['pais'] . ", " . $d['provincia'] . ", " . $d['casillero'] ?></option>;
                            <?php } ?>				                              
                        </select> 
                        <label for="">Numero de tarjeta: </label>
                        <select id="txt_num_tarjeta" name="txt_num_tarjeta" class="form-select-sm cajatexto" aria-label=".form-select-sm example" style="width: 50%; display: inline; margin: 10px 0px 10px 10px;">
                            <option selected value="0">Seleccione la tarjeta a utilizar</option>	
                            <?php foreach ($metodos_pago as $m) { ?>                                      
                                <option value="<?php echo $m['numero_tarjeta'] ?>"><?php echo $m['numero_tarjeta'] ?></option>;
                            <?php } ?>					                              
                        </select> 
                        <label for="">Codigo CVV: </label>
                        <input type="text" name="txt_codigo_cvv_pago" class="cajatexto" id="txt_codigo_cvv_pago" style="width: 50%; margin: 10px 0px 10px 10px;"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="cajatexto"  style="margin-left: 0px;">Confirmar compra de los productos</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>                                                    
                </div>
                <?php echo form_close(); ?>  
            </div>
        </div>
    </div>
    <!-- Ventana flotante para reporte -->
    <div class="modal fade" id="reportes_modal" tabindex="-1" aria-labelledby="reportes_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportes_modalLabel">Generar reporte de productos baratos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?php echo form_open('Reporte/ReporteProductos/');?>   
                <div class="modal-body">
                    <label for="txt_descripcion" class="control-label "><span class="text-danger">* </span>Seleccione una categoría:</label>
                    <div class="form-group">                        
                        <select id="txt_categorias_id" name="txt_categorias_id" class="cajatexto" aria-label=".form-select-sm example">
                            <option value="0" selected>Categorias</option>
                            <?php foreach ($categorias as $c) { 
                                echo "<option value=". $c['idCategorias'] .">". $c['nombre'] ." </option>";
                            } ?>
                        </select>
                        
                        <label for="txt_rangoPrecio" class="control-label "><span class="text-danger">* </span>Productos con precio menor a:</label>
                        <input type="number" name="txt_rangoPrecio" class="cajatexto" id="txt_rangoPrecio" min='0'/><br>
                        
                        <label for="txt_rangoFecha1" class="control-label "><span class="text-danger">* </span>Fecha inicial:</label><br>
                        <input type="date" name="txt_rangoFecha1" class="cajatexto" id="txt_rangoFecha1"/><br>
                        
                        <label for="txt_rangoFecha2" class="control-label "><span class="text-danger">* </span>Fecha final:</label><br>
                        <input type="date" name="txt_rangoFecha2" class="cajatexto" id="txt_rangoFecha2"/>
                        <span class="text-danger"><?php echo form_error('txt_descripcion');?></span>
                    </div>
                </div>

                <div class="modal-footer">                                            
                    <button type="submit" class="btn btn-primary">Generar Reporte</button>                                
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>                                
                </div>

            <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</body>

<footer class="text-center text-lg-start footer">
    <div class="text-center p-4">
        © 2021 Copyright:
        <a class="text-reset fw-bold" href="">Mosqueteros</a>
    </div>
</footer>