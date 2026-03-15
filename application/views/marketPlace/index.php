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
            <div class="col" style="margin-top: 40px;">
                <?php echo form_open('marketPlace/buscar');?>
                    <div class="input-group col busqueda" >
                        <div class="input-group-prepend">
                            <div class="input-group-text" id="btnGroupAddon">
                                <select id="txt_categoria" name="txt_categoria" class="form-select form-select-sm" aria-label=".form-select-sm example">
                                    <option value="0" selected>Categorias</option>
                                    <?php foreach ($categorias as $c) { 
                                        echo "<option value=". $c['nombre'] .">". $c['nombre'] ." </option>";
                                    } ?>
                                </select>
                                <select id="txt_tipo" name="txt_tipo" style="margin-left: 5px;" class="form-select form-select-sm" aria-label=".form-select-sm example">                                    
                                    <option value="0" selected>Tipo</option>    
                                    <option value="Tiendas">Tiendas</option>
                                    <option value="Productos">Productos</option>                                    
                                </select>
                            </div>
                        </div>                                    
                        <input type="text" class="form-control" name="txt_buscar" id="txt_buscar" style="border: 0px;" placeholder="Buscar" title="Buscar">                                  
                        <button id="btn_buscar" type="submit"></button>                                                            
                    </div>
                <?php echo form_close(); ?>
            </div>
            
            <?php if (isset($this->session->userdata['logged_in']['logged_in']) && $this->session->userdata['logged_in']['logged_in'] == TRUE) { ?>

				<div id="perfil_bar" class="col">
					<?php 
						echo "<input class='perfil' type='image' src='" . site_url('resources/img/icon_bandeja_entrada.svg') . "' alt='Bandeja de entrada' title='Bandeja de entrada' width=50 />";
					?> 

					<?php 
						echo "<input class='perfil' type='image' src='" . site_url('resources/img/icon_reporte.svg') . "' alt='Reportes' title='Reportes' width=50 data-bs-toggle='modal' data-bs-target='#reportes_modal'/>";                            
					?>  

                    <a href="<?php echo site_url('marketPlace/carrito/' . $this->session->userdata['logged_in']['users_id']); ?>" >
                        <?php 
                            echo "<input class='perfil' type='image' src='" . site_url('resources/img/carrito.svg') . "' alt='Carrito de compras' title='Carrito de compras'  width=50 />";
                        ?> 
                    </a> 

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
    <!-- Si es una tienda, se redirecciona al panel de administracion -->
    <?php if (isset($this->session->userdata['logged_in']['logged_in']) && $this->session->userdata['logged_in']['logged_in'] == TRUE && $this->session->userdata['logged_in']['tipo'] == "Tienda") { 

        redirect('tienda/index/'.$this->session->userdata['logged_in']['users_id']); ?>
  
    <?php } else { ?>
        <h1 style="margin: 0px 20px 20px 20px">Tiendas</h1>
        
        <div class="container-fluid">
            <div class="row product_box">
                <?php foreach ($tiendas as $t) { ?>                      
                    <div class="col">
                        <a href="<?php echo site_url('tienda/index/' . $t['idUsuarios']); ?>" >
                            <?php 
                                echo "<input class='logo_pt' type='image' src='" . site_url('resources/photos/' . $t['foto_perfil']) . "' alt='Logo' max-width=270px height=200px/>";
                                echo"<br>";
                                echo "<label class='lbl_pt'>" . $t['nombre'] . "</label>";
                            ?>   
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>

    <h1 style="margin: 20px 20px 20px 20px">Productos</h1>
    <div class="container-fluid">
        <div class="row product_box">
            <?php 
            if($productos !=null) {
                foreach ($productos as $p) { 
                    $foto = '';
                    foreach ($fotos as $f) { 
                        if($f['Productos_id'] == $p['idProductos']){
                            $foto = $f['nombre'];
                            break;
                        }
                    } ?>  
                    
                    <div class="col">
                        <a href="<?php echo site_url('producto/index/'. $p['idProductos']); ?>" >
                            <?php 
                                echo "<input class='logo_pt' style='border-radius: 10px' type='image' src='" . site_url('resources/photos/products/' . $foto) . "' alt='Logo' max-width=270px height=200px/>";
                                echo"<br>";
                                echo "<label class='lbl_pt'>" . $p['nombre'] . "</label>";
                            ?>   
                        </a>
                    </div>
                <?php }             
            }?>
        </div>
    </div>
    <?php } ?>
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
