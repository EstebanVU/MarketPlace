<?php if (!empty($this->session)) { 
		if($this->session->flashdata('error')){ echo "<div class='msg_box_user error' >" .  $this->session->flashdata('error') . "</div>"; } 
		if($this->session->flashdata('success')){ echo "<div class='msg_box_user success' >" .  $this->session->flashdata('success') . "</div>"; } 
} ?>

<head>  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

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
                <?php echo form_open('tienda/buscar/' . $tienda['idUsuarios']);?>
                    <div class="input-group col busqueda" >
                        <div class="input-group-prepend">
                            <div class="input-group-text" id="btnGroupAddon">
                                <select id="txt_categoria" name="txt_categoria" class="form-select form-select-sm" aria-label=".form-select-sm example">
                                    <option value="0" selected>Categorias</option>
                                    <?php foreach ($categorias as $c) { 
                                        echo "<option value=". $c['nombre'] .">". $c['nombre'] ." </option>";
                                    } ?>
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

    <div class="container-fluid">
        <div class="row">
            <div class="col" id="nombre_tienda">
                <h1 style="margin: 20px 20px 20px 20px"><?php echo $tienda['nombre'] ?></h1>        
            </div>
        </div>
        <div class="row" id="datos_principales">
            <div class="col" id="img_pr">
                <a href="<?php echo site_url("tienda/index/" . $tienda['idUsuarios']) ?>">
                    <?php echo "<img class='logo_pt' style='border-radius: 10px' src='". site_url('resources/photos/' . $tienda['foto_perfil']) ."' alt='Logo' width=200 height=120> ";?>
                </a>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        <label for="" style="font-weight: bold;">Telefono:</label>
                    </div>
                    <div class="col">
                        <label for=""><?php echo $tienda['telefono'] ?></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="" style="font-weight: bold;">Pais de origen:</label>
                    </div>
                    <div class="col">
                        <label for=""><?php echo $tienda['pais'] ?></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="" style="font-weight: bold;">Provincia:</label>
                    </div>
                    <div class="col">
                        <label for=""><?php echo $tienda['provincia'] ?></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="" style="font-weight: bold;">Correo de contacto:</label>
                    </div>
                    <div class="col">
                        <label for=""><?php echo $tienda['email'] ?></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="" style="font-weight: bold;">Calificacion:</label>
                    </div>
                    <div class="col">
                        <label for=""><?php echo number_format($calificacion['calificacionT'],2) ?> ⭐</label>
                    </div>
                </div>
                <?php foreach ($redes as $r) { ?>      
                    <div class="row">
                        <div class="col">
                            <label for="" style="font-weight: bold;"><?php echo $r['nombre'] ?>:</label>
                        </div>
                        <div class="col">
                            <label for=""><?php echo $r['url'] ?></label>
                        </div>
                    </div>
                <?php } ?>
            </div>     
            <?php if (isset($this->session->userdata['logged_in']['logged_in']) && $this->session->userdata['logged_in']['logged_in'] == TRUE && $this->session->userdata['logged_in']['tipo'] == 'Cliente') { ?>
                <div class="container-fluid">
                    <div class="row">

                        <div class="col">
                            <?php echo form_open('tienda/suscribirse/'. $tienda['idUsuarios']);?>                   
                                <button type="submit" class="cajatexto" style="margin-top: 30px;">Suscribirse</button>
                            <?php echo form_close(); ?>   
                        </div>

                        <div class="col">
                            <button type="button" style="margin-top: 30px;" class="cajatexto" data-bs-toggle="modal" data-bs-target="#denuncias_modal" id="btn_reportar">Reportar abuso</button> <!--Activa la ventana flotante-->      
                        </div>
                    </div>
                </div>

                <!-- Ventana flotante para reportar tienda-->
                <div class="modal fade" id="denuncias_modal" tabindex="-1" aria-labelledby="denuncias_modalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="denuncias_modalLabel">Reportar abuso</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <?php echo form_open('tienda/reportar_abuso/'. $tienda['idUsuarios']);?>   

                            <div class="modal-body">

                                <label for="txt_descripcion" class="control-label "><span class="text-danger">* </span>Descripción del reporte:</label>
                                <div class="form-group">                        
                                    <input type="text" name="txt_descripcion" class="cajatexto" id="txt_descripcion" />
                                    <span class="text-danger"><?php echo form_error('txt_descripcion');?></span>
                                </div>
                                
                                <label for="txt_tipo_d" class="control-label "><span class="text-danger">* </span>Tipo de reporte:</label>
                                <div class="form-group">  
                                    <select id="txt_tipo_d" name="txt_tipo_d" class="cajatexto" aria-label=".form-select-sm example">
                                        <option value="0" selected>Tipo de reporte</option>
                                        <option value="Falta de información">Falta de información</option>                                        
                                        <option value="Contenido inapropiado">Contenido inapropiado</option>                                        
                                        <option value="Contenido diferente al del producto">Contenido diferente al del producto</option>
                                        <option value="Contenido engañoso">Contenido engañoso</option>
                                        <option value="Otro motivo">Otro motivo</option>
                                    </select>
                                </div>

                            </div>

                            <div class="modal-footer">                                            
                                <button type="submit" class="btn btn-primary">Reportar abuso</button>                                
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>                                
                            </div>

                        <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>            
        </div>
    </div>
    <?php if (isset($this->session->userdata['logged_in']['logged_in']) && $this->session->userdata['logged_in']['logged_in'] == TRUE && $this->session->userdata['logged_in']['users_id'] == $tienda['idUsuarios']) { ?>
        <?php echo form_open_multipart('tienda/agregar_producto/' . $tienda['idUsuarios']); ?>
            <div class="container-fluid">
                <div class="row" id="insertar_producto">
                    <div class="col">
                        <label for="txt_nombre" class="control-label "><span class="text-danger">* </span>Nombre:</label>
                        <div class="form-group">
                            <input type="text" name="txt_nombre" value="<?php echo $this->input->post('txt_nombre'); ?>" class="cajatexto" id="txt_nombre" />
                            <span class="text-danger"><?php echo form_error('txt_nombre');?></span>
                        </div>
                    </div>
                    <div class="col">
                        <label for="txt_descripcion" class="control-label "><span class="text-danger">* </span>Descripcion:</label>
                        <div class="form-group">
                            <input type="text" name="txt_descripcion" value="<?php echo $this->input->post('txt_descripcion'); ?>" class="cajatexto" id="txt_descripcion" />
                            <span class="text-danger"><?php echo form_error('txt_descripcion');?></span>
                        </div>
                    </div>
                    <div class="col">
                        <label for="txt_disponibles" class="control-label "><span class="text-danger">* </span>Disponibles:</label>
                        <div class="form-group">                        
                        <input type="number" min="1" name="txt_disponibles" value="<?php echo $this->input->post('txt_disponibles'); ?>" class="cajatexto" id="txt_disponibles" />
                            <span class="text-danger"><?php echo form_error('txt_disponibles');?></span>
                        </div>
                    </div>
                    <div class="col">
                        <label for="txt_ubicacion" class="control-label "><span class="text-danger">* </span>Ubicacion:</label>
                        <div class="form-group">                        
                        <input type="text" name="txt_ubicacion" value="<?php echo $this->input->post('txt_ubicacion'); ?>" class="cajatexto" id="txt_ubicacion" />
                            <span class="text-danger"><?php echo form_error('txt_ubicacion');?></span>
                        </div>
                    </div>
                    <div class="col">
                        <label for="txt_precio" class="control-label "><span class="text-danger">* </span>Precio:</label>
                        <div class="form-group">                        
                        <input type="number" min="1" name="txt_precio" value="<?php echo $this->input->post('txt_precio'); ?>" class="cajatexto" id="txt_precio" />
                            <span class="text-danger"><?php echo form_error('txt_precio');?></span>
                        </div>
                    </div>
                    <div class="col">
                        <label for="txt_tiempo_envio" class="control-label "><span class="text-danger">* </span>Tiempo de envio (dias):</label>
                        <div class="form-group">                        
                        <input type="number" min="1" name="txt_tiempo_envio" value="<?php echo $this->input->post('txt_tiempo_envio'); ?>" class="cajatexto" id="txt_tiempo_envio" />
                            <span class="text-danger"><?php echo form_error('txt_tiempo_envio');?></span>
                        </div>
                    </div>
                    <div class="col">
                        <label for="txt_costo_envio" class="control-label "><span class="text-danger">* </span>Costo del envio:</label>
                        <div class="form-group">                        
                        <input type="number" min="1" name="txt_costo_envio" value="<?php echo $this->input->post('txt_tiempo_envio'); ?>" class="cajatexto" id="txt_costo_envio" />
                            <span class="text-danger"><?php echo form_error('txt_costo_envio');?></span>
                        </div>
                    </div>
                    <div class="col">
                        <label for="txt_foto" class="control-label "><span class="text-danger">* </span>Foto del producto:</label>
                        <div class="form-group">                        
                        <input type="file" name="txt_foto" size="20" accept="image/jpeg,image/gif,image/png" value="<?php echo $this->input->post('txt_foto'); ?>" class="cajatexto" id="txt_foto"/>
                            <span class="text-danger"><?php echo form_error('txt_foto');?></span>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="box-footer" style="text-align: center;">
                <button type="submit" class="boton">Agregar producto</button>                        
            </div>
        <?php echo form_close(); ?>

    <div style="text-align: center;">
        <button type="button" class="boton" data-bs-toggle="modal" data-bs-target="#suscripciones_modal" id="btn_suscripciones">Ver suscripciones</button> <!--Activa la ventana flotante-->          
        <button type="button" class="boton" data-bs-toggle="modal" data-bs-target="#reporte_venta_modal" id="btn_suscripciones">Ver reporte de ventas</button> <!--Activa la ventana flotante-->          
    </div>

    <div style="text-align: center; margin-top: 18px ;">
        <button type="button" class=" boton" data-bs-toggle="modal" data-bs-target="#deseos_modal">Ver usuarios con productos deseados</button> <!--Activa la ventana flotante-->  
    </div>

    <!-- Ventana flotante de usuarios suscritos -->
    <div class="modal fade" id="suscripciones_modal" tabindex="-1" aria-labelledby="suscripciones_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="suscripciones_modalLabel">Suscripciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php foreach ($suscripciones as $s) { 
                    echo "<div class='row'>";
                    echo "<div class='col'> <label>".$s['nombre']."</label></div>";
                    echo "<div class='col' style='text-align: right;'><label>".$s['email']."</label></div>";
                    echo "</div>";
                }?>            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Ventana flotante de usuarios con productos en la lista de deseos -->
    <div class="modal fade" id="deseos_modal" tabindex="-1" aria-labelledby="deseos_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deseos_modalLabel">Usuarios con productos deseados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php foreach ($usuarios_deseos as $s) { 
                    echo "<div class='row'>";
                    echo "<div class='col'><label>".$s['nombre']."</label></div>";
                    echo "<div class='col' style='text-align: right;'><label>".$s['email']."</label></div>";
                    echo "</div>";
                }?>            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <h1 style="margin: 20px 20px 20px 20px">Productos</h1>            
    <div class="container-fluid">
        <div class="row product_box">
            <?php foreach ($productos as $p) { 
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
            <?php } ?>
        </div>
    </div>

    <!-- Ventana flotante para reporte de ventas-->
    <div class="modal fade" id="reporte_venta_modal" tabindex="-1" aria-labelledby="reporte_venta_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reporte_venta_modalLabel">Generar reporte de ventas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <?php echo form_open('Reporte/ReporteVentas/');?>   
                <div class="modal-body">                    
                    <div class="form-group">                        
                        <label for="txt_rangoFecha1">Fecha inicial:</label>
                        <input type="date" name="txt_rangoFecha1" class="cajatexto"/>
                        <label for="txt_rangoFecha2">Fecha final:</label>
                        <input type="date" name="txt_rangoFecha2" class="cajatexto"/>
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