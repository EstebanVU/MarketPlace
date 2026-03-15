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
<?php if (isset($this->session->userdata['logged_in']['logged_in']) && $this->session->userdata['logged_in']['logged_in'] == TRUE && $this->session->userdata['logged_in']['users_id'] == $producto['Usuarios_id']) { ?>
        
            <div class="container-fluid">
                <div class="row">
                    <div class="col" style="text-align: center;">
                        <?php echo form_open('producto/editar_producto/' . $producto['idProductos']); ?>
                        <h3>Datos del producto</h3>
                        <label for="txt_nombre" class="control-label"><span class="text-danger">* </span>Nombre:</label>
                        <div class="form-group">
                            <input type="text" name="txt_nombre" value="<?php echo ($this->input->post('txt_nombre') ? $this->input->post('txt_nombre') : $producto['nombre']); ?>" class="cajatexto" id="txt_nombre" />
                            <span class="text-danger"><?php echo form_error('txt_nombre');?></span>
                        </div>
                        <label for="txt_descripcion" class="control-label"><span class="text-danger">* </span>Descripcion:</label>
                        <div class="form-group">
                            <input type="text" name="txt_descripcion" value="<?php echo ($this->input->post('txt_descripcion') ? $this->input->post('txt_descripcion') : $producto['descripcion']); ?>" class="cajatexto" id="txt_descripcion" />
                            <span class="text-danger"><?php echo form_error('txt_descripcion');?></span>
                        </div>
                        <label for="txt_disponibles" class="control-label"><span class="text-danger">* </span>Disponibles:</label>
                        <div class="form-group">
                            <input type="number" min="1" name="txt_disponibles" value="<?php echo ($this->input->post('txt_disponibles') ? $this->input->post('txt_disponibles') : $producto['disponibles']); ?>" class="cajatexto" id="txt_disponibles" />
                            <span class="text-danger"><?php echo form_error('txt_disponibles');?></span>
                        </div>
                        <label for="txt_ubicacion" class="control-label "><span class="text-danger">* </span>Ubicacion:</label>
                        <div class="form-group">                        
                        <input type="text" name="txt_ubicacion" value="<?php echo ($this->input->post('txt_ubicacion') ? $this->input->post('txt_ubicacion') : $producto['ubicacion']); ?>" class="cajatexto" id="txt_ubicacion" />
                            <span class="text-danger"><?php echo form_error('txt_ubicacion');?></span>
                        </div>
                        <label for="txt_precio" class="control-label "><span class="text-danger">* </span>Precio:</label>
                        <div class="form-group">                        
                        <input type="number" min="1" name="txt_precio" value="<?php echo ($this->input->post('txt_precio') ? $this->input->post('txt_precio') : $producto['precio']); ?>" class="cajatexto" id="txt_precio" />
                            <span class="text-danger"><?php echo form_error('txt_precio');?></span>
                        </div>
                        <label for="txt_tiempo_envio" class="control-label "><span class="text-danger">* </span>Tiempo de envio (dias):</label>
                        <div class="form-group">                        
                        <input type="number" min="1" name="txt_tiempo_envio" value="<?php echo ($this->input->post('txt_tiempo_envio') ? $this->input->post('txt_tiempo_envio') : $producto['tiempo_envio']); ?>" class="cajatexto" id="txt_tiempo_envio" />
                            <span class="text-danger"><?php echo form_error('txt_tiempo_envio');?></span>
                        </div>
                        <label for="txt_costo_envio" class="control-label "><span class="text-danger">* </span>Costo del envio:</label>
                        <div class="form-group">                        
                        <input type="number" min="1" name="txt_costo_envio" value="<?php echo ($this->input->post('txt_tiempo_envio') ? $this->input->post('txt_costo_envio') : $producto['costo_envio']); ?>" class="cajatexto" id="txt_costo_envio" />
                            <span class="text-danger"><?php echo form_error('txt_costo_envio');?></span>
                        </div>   
                        <button type="submit" class="boton" style="margin: 10px;">Editar producto</button>     
                        <?php echo form_close(); ?> 

                        <?php echo form_open('producto/eliminar_producto/' . $producto['idProductos']);?>                        
                            <button type="submit" class="boton">Eliminar producto</button>    
                        <?php echo form_close(); ?>          
                   </div>  
                     

                    <div class="col" style="text-align: center;">
                        
                        <h3>Categorias del producto</h3>  
                        
                            <?php foreach ($categorias_producto as $c) {  
                                echo form_open('producto/eliminar_categoria_producto/' . $producto['idProductos']);
                                    echo "<label style='font-size: 20px;'>" . $c['nombre'] . "</label>"; 
                                    echo "<input type='hidden' name='txt_categoria' value='". $c['idCategorias']. "'>";                                
                                    echo "<button style='margin-left:10px;' type='submit' class='boton_x'>X</button><br>";
                                echo form_close(); 
                            } ?>
                         
                        <h3 style="margin: 0px 20px 20px 20px">Agregar categoría al producto</h3>  
                        <?php echo form_open('producto/agregar_categoria_producto/' . $producto['idProductos']);?>                        
                            <select id="txt_categoria" name="txt_categoria" class="form-select form-select-sm" aria-label=".form-select-sm example" style="width: 50%; display: inline; margin-top: 10px;">
                                <option selected value="0">Seleccione una categoría</option>
                                <?php foreach ($categorias as $c) {  
                                    echo "<option value=". $c['idCategorias'] .">". $c['nombre'] ." </option>";
                                } ?>                                
                            </select> 
                            <br>
                            <br>
                            <label>Categoría personalizada:</label> 
                            <?php echo "<input id='txt_nueva_categoria' type='text' name='txt_nueva_categoria'><br><br>"; ?>
                            <button type="submit" class="boton">Agregar categoría</button>
                        <?php echo form_close(); ?>  

                        <h3 style="margin: 20px 20px 20px 20px">Agregar imagenes del producto</h3>  
                        <?php echo form_open_multipart('producto/upload_photo/' . $producto['idProductos']);?>
                            <input type="file" name="txt_foto" size="20" class="form-control-file" accept="image/jpeg,image/gif,image/png" />
                            <br><br>
                            <button type="submit" class="boton">Cargar Foto</button>
                        <?php echo form_close(); ?>       
                    </div>  
                </div>                    
            </div>                      
    <?php } else { ?>
        <div class="container-fluid">
        
            <div class="row">
                <div class="col" id="nombre_tienda">
                    <h1 style="margin: 20px 20px 20px 20px"><?php echo $producto['nombre'] ?></h1>        
                </div>
            </div>

            <div class="row" id="datos_principales">
                <div class="col">
                    <div class="row">
                        <div class="col">
                            <label for="" style="font-weight: bold;">Descripcion:</label>
                        </div>
                        <div class="col">
                            <label for=""><?php echo $producto['descripcion'] ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="" style="font-weight: bold;">Cantidad disponible:</label>
                        </div>
                        <div class="col">
                            <label for=""><?php echo $producto['disponibles'] ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="" style="font-weight: bold;">Fecha de publicación:</label>
                        </div>
                        <div class="col">
                            <label for=""><?php echo $producto['fecha_publicacion'] ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="" style="font-weight: bold;">Ubicación:</label>
                        </div>
                        <div class="col">
                            <label for=""><?php echo $producto['ubicacion'] ?></label>
                        </div>
                    </div>                               
                    <div class="row">
                        <div class="col">
                            <label for="" style="font-weight: bold;">Tiempo de envio (dias):</label>
                        </div>
                        <div class="col">
                            <label for=""><?php echo $producto['tiempo_envio'] ?></label>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col">
                            <label for="" style="font-weight: bold;">Costo de envio:</label>
                        </div>
                        <div class="col">
                            <label for=""><?php echo $producto['costo_envio'] ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="" style="font-weight: bold;">Precio:</label>
                        </div>
                        <div class="col">
                            <label for=""><?php echo $producto['precio'] ?></label>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col">
                            <label for="" style="font-weight: bold;">Calificacion:  </label>
                        </div>
                        <div class="col">
                            <label for=""><?php echo number_format($calificacion['calificacionP'],2)?> ⭐</label>
                        </div>
                    </div>                       
                </div>     
            </div>

            <?php if(isset($this->session->userdata['logged_in']['logged_in']) && $this->session->userdata['logged_in']['logged_in'] == TRUE) { ?>
                <div style="margin-top: 20px;">
                    <div class="container-fluid">
                        <?php echo form_open('marketPlace/agregar_carrito/' . $producto['idProductos']);?>     
                            <div class="row">
                                
                                <div class="col" style="text-align: right; ">                   
                                    <button type="submit" class="cajatexto">Agregar al carrito</button>                                    
                                </div>
                                <div class="col">
                                    <input type="number" min="1" name="txt_cantidad" value=1 class="cajatexto" id="txt_cantidad" />
                                </div>                                
                            </div>
                        <?php echo form_close(); ?>

                        <div class="row" style="text-align: center;">
                            <?php echo form_open('marketPlace/agregar_deseo/' . $producto['idProductos']);?>                   
                                <button type="submit" class="cajatexto">Agregar a la lista de deseos</button>
                            <?php echo form_close(); ?>

                            <?php echo form_open('marketPlace/agregar_calificacion/' . $producto['idProductos']);?>                   
                                <select id="txt_calificacion" name="txt_calificacion" class="form-select form-select-sm" aria-label=".form-select-sm example">
                                    <option value="0" selected>Calificaciones</option>
                                    <option value="1">⭐</option>
                                    <option value="2">⭐⭐</option>
                                    <option value="3">⭐⭐⭐</option> 
                                    <option value="4">⭐⭐⭐⭐</option> 
                                    <option value="5">⭐⭐⭐⭐⭐</option>                
                                </select>
                                <button type="submit" class="cajatexto">Calificar Producto</button>
                            <?php echo form_close(); ?>
                        </div>

                        
                        
                    </div>
                </div>
            <?php } else { ?>
                <div class="col" id="nombre_tienda">
                 <h3 style="margin: 20px 20px 20px 20px" class="">Inicie sesión para comprar el producto</h3>
                </div>                
            <?php } ?>

        </div>
            
        
    <?php } ?>

        <h1 style="margin: 20px 20px 20px 20px">Comentarios</h1> 
        <div class="row product_box">
            <div class = "container_comments">
                <?php foreach ($comentarios as $c) {   

                    $bandR = FALSE; //Bandera para identificar si el comentario ya cuenta con una respuesta
                    $bandC = FALSE; ?> <!-- Bandera para identificar si el comentario es una respuesta de otro comentario -->

                    <?php for($i = 0; $i < sizeof($respuestas); $i++) {
                        if($respuestas[$i]!=NULL) {
                            if($respuestas[$i]['idComentarios']==$c['idComentarios']) { 
                                $bandC = TRUE; ?>                                                                     
                        <?php }
                        }
                    } ?>
                    
                    <?php if($bandC == FALSE) { ?>
                        <div class="comentarios">  
                            <h5><?php echo "Publicado por ".$c['nombre'];?></h5>
                            <div style="margin-bottom:20px">
                                <?php echo $c['descripcion']; ?>
                            </div>  
                        <?php 

                         for($i = 0; $i < sizeof($respuestas); $i++) {
                            if($respuestas[$i]!=NULL) {
                                if($respuestas[$i]['idComentarios']==$c['Comentarios_id']) { ?>
                                    <div>
                                        <h5><?php echo "Respuesta: "?></h5>
                                        <h5><?php echo "Publicado por ".$respuestas[$i]['nombre'];?></h5>
                                        <div style="margin-bottom:20px">
                                            <?php echo $respuestas[$i]['descripcion']; ?>
                                        </div>
                                    </div>
                                <?php $bandR = TRUE;
                                }
                            }
                        }?> 
                            
                        <?php if (isset($this->session->userdata['logged_in']['logged_in']) && $this->session->userdata['logged_in']['logged_in'] == TRUE && $this->session->userdata['logged_in']['users_id'] == $producto['Usuarios_id'] && $bandR == FALSE) {
                                echo form_open('Producto/agregar_comentario_respuesta/' . $producto['idProductos'] .'/'. $c['idComentarios']);?>
                                    <textarea class="form-control" id="text_respuesta" name="text_respuesta" rows="2"></textarea>
                                    <button type="submit" class="cajatexto">Responder</button>
                                <?php echo form_close();
                            } ?>
                    </div>  
                <?php } 
                }?>      
            </div>

            <div class="form-group escribirComentario">
                <?php echo form_open('marketPlace/agregar_comentario/' . $producto['idProductos']);
                    if (isset($this->session->userdata['logged_in']['logged_in']) && $this->session->userdata['logged_in']['logged_in'] == TRUE && $this->session->userdata['logged_in']['users_id'] != $producto['Usuarios_id']){?>               
                        <textarea class="form-control" id="text_comentario" name="text_comentario"  rows="3"></textarea>
                        <button type="submit" class="cajatexto">Comentar</button>
                <?php } ?>
                <?php echo form_close();?>
            </div>  
        </div> 
    <h1 style="margin: 20px 20px 20px 20px">Galeria de imagenes del producto</h1>            
        <div class="container-fluid">
            <div class="row product_box">
                <?php foreach ($fotos as $f) {  ?>
                    <div class="col" style="margin-bottom: 20px;"> 
                    <?php
                        echo "<img class='logo_pt' style='border-radius: 10px' src='".site_url('resources/photos/products/' . $f['nombre']) . "' alt='Logo' max-width=270px height=200px> ";                        
                    ?>
                    </div>
                <?php } ?>  
                    
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
