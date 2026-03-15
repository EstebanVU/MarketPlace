

<?php if($this->session->userdata['logged_in']['users_id']==$user['idUsuarios'] && $this->session->userdata['logged_in']['logged_in']==TRUE ){ ?>

	<div id="panel_app" style="overflow-x: hidden;">

		<?php if (!empty($this->session)) { 
			if($this->session->flashdata('error')){ echo "<div class='msg_box_user error' >" .  $this->session->flashdata('error') . "</div>"; } 
			if($this->session->flashdata('success')){ echo "<div class='msg_box_user success' >" .  $this->session->flashdata('success') . "</div>"; } 
		} ?>

	    <div class="box-header">
			<div style="text-align: right;">
				<?php echo form_open('marketPlace/index');?>
					<button type="submit" name="btn_return" id="btn_return" class="boton" title="Regresar" style="margin-right: 20px;" >←</button>
				<?php echo form_close();?>
			</div>

			<h2 class="box-title lbls_">Editando Usuario</h2>	      	
	    </div>

		<div id="edit_panel container-fluid">
			<?php echo "<img src='" . site_url('resources/photos/' . $this->session->userdata['logged_in']['photo']) 
				. "' alt='Editar Foto' title='Editar Foto' width=70 height=70 id='photo_profile' />"; ?>
			<br>
			<?php echo form_open_multipart('user/upload_photo/' . $user['idUsuarios']);?>
				<input type="file" name="txt_file" size="20" class="btn btn-info" accept="image/jpeg,image/gif,image/png" />
				<br><br>
				<button type="submit" class="boton">Cargar Foto</button>
			<?php echo form_close(); ?>			
			  			
			<div class="row" id = "datos_box" >
				<div class="col">			
					<?php echo form_open('user/edit/'.$user['idUsuarios']); ?>	
						<label for="txt_nombre" class="control-label lbls_"><span class="text-danger">* </span>Nombre:</label>
						<div class="form-group">
							<input type="text" name="txt_nombre" value="<?php echo ($this->input->post('txt_nombre') ? $this->input->post('txt_nombre') : $user['nombre']); ?>" class="cajatexto" id="txt_nombre" />
							<span class="text-danger"><?php echo form_error('txt_nombre');?></span>
						</div>

						<label for="txt_cedula" class="control-label lbls_"><span class="text-danger">* </span>Cedula:</label>
						<div class="form-group">
							<input type="text" name="txt_cedula" value="<?php echo ($this->input->post('txt_cedula') ? $this->input->post('txt_cedula') : $user['cedula']); ?>" class="cajatexto" id="txt_cedula" />
							<span class="text-danger"><?php echo form_error('txt_cedula');?></span>
						</div>

						<label for="txt_correo" class="control-label lbls_"><span class="text-danger">* </span>Correo:</label>
						<div class="form-group">
							<input type="text" name="txt_correo" value="<?php echo ($this->input->post('txt_correo') ? $this->input->post('txt_correo') : $user['email']); ?>" class="cajatexto" id="txt_correo" />
							<span class="text-danger"><?php echo form_error('txt_correo');?></span>
						</div>
						
						<label for="txt_telefono" class="control-label lbls_"><span class="text-danger">* </span>Telefono:</label>
						<div class="form-group">
							<input type="text" name="txt_telefono" value="<?php echo ($this->input->post('txt_telefono') ? $this->input->post('txt_telefono') : $user['telefono']); ?>" class="cajatexto" id="txt_telefono" />
							<span class="text-danger"><?php echo form_error('txt_telefono');?></span>
						</div>
						
						<label for="txt_pais" class="control-label lbls_"><span class="text-danger">* </span>Pais:</label>
						<div class="form-group">
							<input type="text" name="txt_pais" value="<?php echo ($this->input->post('txt_pais') ? $this->input->post('txt_pais') : $user['pais']); ?>" class="cajatexto" id="txt_pais" />
							<span class="text-danger"><?php echo form_error('txt_pais');?></span>
						</div>

						<label for="txt_provincia" class="control-label lbls_"><span class="text-danger">* </span>Provincia:</label>
						<div class="form-group">
							<input type="text" name="txt_provincia" value="<?php echo ($this->input->post('txt_provincia') ? $this->input->post('txt_provincia') : $user['provincia']); ?>" class="cajatexto" id="txt_provincia" />
							<span class="text-danger"><?php echo form_error('txt_provincia');?></span>
						</div>
						
						<br>
						<div class="box-footer">
							<button type="submit" class="boton">Guardar</button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
			
			<?php if($user['tipo'] == "Cliente") { ?>
				<div class="row" style="margin: 20px 0px 50px 0px;">
					<div>			
						<?php echo form_open('Reporte/ReporteSuscripciones/');?> 				
							<button type='submit' class='boton'>Ver reporte de suscripciones</button><br>
						<?php echo form_close();  ?>						
						
						<button id="btn_direccion_modal" type="button" class="boton" data-bs-toggle="modal" data-bs-target="#reporte_suscripciones_modal">Ver reporte de compras</button> <!--Activa la ventana flotante-->  															
					</div>
				</div>			
			<?php }?>
			<div class="row" style="margin: 20px 0px 20px 0px;">
				<div class="col">
					<button id="btn_red_modal" type="button" class=" boton" data-bs-toggle="modal" data-bs-target="#red_modal">Agregar red social</button> <!--Activa la ventana flotante-->  										
				</div>
				<?php if($user['tipo'] == "Cliente") { ?>
					<div class="col">
						<button id="btn_direccion_modal" type="button" class=" boton" data-bs-toggle="modal" data-bs-target="#direccion_modal">Agregar dirección de envío</button> <!--Activa la ventana flotante-->  										
					</div>
					<div class="col">
						<button id="btn_metod_pago_modal" type="button" class=" boton" data-bs-toggle="modal" data-bs-target="#metodo_pago_modal">Agregar método de pago</button> <!--Activa la ventana flotante-->  					
					</div>
				<?php }?>
			</div>			

			<div class="row">
				<div class="col">
					<h3 class="lbls_">Redes sociales</h3>
					<div class="row">					
						<?php foreach ($redes as $r) { ?>      
							<?php echo form_open('user/eliminar_red/' . $r['idRedesSociales_Usuarios']); ?>
								<div class="row">									
									<div>
										<label class="lbls_" style="font-weight: bold;"><?php echo $r['nombre'] ?>:</label>
										<label class="lbls_" for=""><?php echo $r['url'] ?></label>
										<button style='margin-left:10px;' type='submit' class='boton_x'>X</button><br>
									</div>									
								</div>
							<?php echo form_close();  ?>
						<?php } ?>
					</div>
				</div>
				<?php if($user['tipo'] == "Cliente") { ?>
					<div class="col">
						<h3 class="lbls_">Direcciones de envio</h3>									
						<div class="row">
							<?php foreach ($direcciones as $d) { ?>      
								<?php echo form_open('user/eliminar_direccion/' . $d['idDirecciones']); ?>
									<div class="row">
										<div>
											<label class="lbls_" style="font-weight: bold;">Pais: </label>
											<label class="lbls_"><?php echo $d['pais'] ?></label>
										</div>
										<div>
											<label class="lbls_" style="font-weight: bold;">Provincia: </label>
											<label class="lbls_"><?php echo $d['provincia'] ?></label>
										</div>
										<div>
											<label class="lbls_" style="font-weight: bold;">Casillero: </label>
											<label class="lbls_"><?php echo $d['casillero'] ?></label>
										</div>
										<div>
											<label class="lbls_" style="font-weight: bold;">Código postal: </label>
											<label class="lbls_"><?php echo $d['postal'] ?></label>
										</div>
										<div>
											<label class="lbls_" style="font-weight: bold;">Observaciones: </label>
											<label class="lbls_"><?php echo $d['observaciones'] ?></label>
										</div>
										<div>
											<button style='margin: 10px 0px 0px 10px;' type='submit' class='boton'>Eliminar dirección</button><br>
										</div>
									</div>
								<?php echo form_close();  ?>
							<?php } ?>
						</div>	
					</div>
					<div class="col">
						<h3 class="lbls_">Formas de pago</h3>
						<div class="row">
							<?php foreach ($metodos_pago as $m) { ?>      
								<?php echo form_open('user/eliminar_metodo_pago/' . $m['idFormas_Pago']); ?>
									<div class="row">
										
										<div>
											<label class="lbls_" style="font-weight: bold;">Titular de la tarjeta: </label>
											<label class="lbls_"><?php echo $m['titular_tarjeta'] ?></label>
										</div>
										<div>
											<label class="lbls_" style="font-weight: bold;">Numero de la tarjeta: </label>
											<label class="lbls_"><?php echo $m['numero_tarjeta'] ?></label>
										</div>
										<div>
											<label class="lbls_" style="font-weight: bold;">Saldo: </label>
											<label class="lbls_"><?php echo $m['saldo'] ?></label>
										</div>
										<div>
											<label class="lbls_" style="font-weight: bold;">Vencimiento: </label>
											<label class="lbls_"><?php echo $m['vencimiento'] ?></label>
										</div>
										<div>
											<button style='margin: 10px 0px 0px 10px;' type='submit' class='boton'>Eliminar metodo de pago</button><br>
										</div>
										
									</div>
								<?php echo form_close();  ?>
							<?php } ?>	
						</div>
					</div>
				<?php }?>
			</div>
					
	        <div id="actions" style="margin-top: 20px;">
				<a href="<?php echo site_url('auth/logout'); ?>" id="btn_salir" name="btn_salir" title="Salir" style="margin-right: 20px;">🚪 Cerrar sesión</a>  		
			  	<br>
              	<a href="<?php echo site_url('user/delete/' . $user['idUsuarios']); ?>" id="btn_eliminar" name="btn_eliminar"  style="margin-right: 20px;" title="Eliminar" >🗙 Eliminar mi cuenta</a>
          	</div>
		</div>
	</div>

	<!-- Ventana flotante para agregar redes sociales -->
    <div class="modal fade" id="red_modal" tabindex="-1" aria-labelledby="red_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="red_modalLabel">Agregar red social</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
				<?php echo form_open('user/agregar_red/');?>                        						
					<select id="txt_red_social" name="txt_red_social" class="form-select form-select-sm" aria-label=".form-select-sm example" style="width: 50%; display: inline; margin: 10px 0px 10px 0px;">
						<option selected value="0">Seleccione una red social</option>							
						<option value="1">Facebook</option>;
						<option value="2">Twitter</option>;
						<option value="3">Instagram</option>;
						<option value="4">Youtube</option>;						                              
					</select> 

					<br><label for="txt_url_red">Url de la red:</label><br>
					<input type="text" name="txt_url_red" class="cajatexto"id="txt_url_red"/><br>			
					<button type="submit" class="boton">Agregar red social</button>
				<?php echo form_close(); ?>   

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>
	<!-- Ventana flotante para agregar direcciones de envio -->
    <div class="modal fade " id="direccion_modal" tabindex="-1" aria-labelledby="direccion_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="direccion_modalLabel">Agregar dirección de envío</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
				<?php echo form_open('user/agregar_direccion/'); ?>
					<div class="row">						
						<div class="col">
							<label for="txt_pais_d">Pais:</label><br>
							<input type="text" name="txt_pais_d" class="cajatexto"id="txt_pais_d" /><br>
						</div>
					</div>
					<div class="row">						
						<div class="col">
							<label for="txt_provincia_d">Provincia:</label><br>
							<input type="text" name="txt_provincia_d" class="cajatexto"id="txt_provincia_d" /><br>
						</div>
					</div>
					<div class="row">						
						<div class="col">
							<label for="txt_casillero">Casillero:</label><br>
							<input type="text" name="txt_casillero" class="cajatexto"id="txt_casillero" /><br>
						</div>
					</div>
					<div class="row">						
						<div class="col">
							<label for="txt_postal">Código Postal:</label><br>
							<input type="text" name="txt_postal" class="cajatexto"id="txt_postal" /><br>
						</div>
					</div>
					<div class="row">						
						<div class="col">
							<label for="txt_observacion">Observaciones:</label><br>
							<input type="text" name="txt_observacion" class="cajatexto"id="txt_observacion" /><br>
						</div>
					</div>
					<div class="col">
						<button type="submit" class="boton">Agregar dirección de envio</button>
					</div>
				<?php echo form_close();  ?>         
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>
	<!-- Ventana flotante para agregar metodos de pago -->
    <div class="modal fade" id="metodo_pago_modal" tabindex="-1" aria-labelledby="metodo_pago_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="metodo_pago_modalLabel">Agregar red social</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
				<?php echo form_open('user/agregar_metodo_pago/'); ?>
					<div class="row">						
						<div class="col">
							<label for="txt_titular_tarjeta">Titular de tarjeta:</label><br>
							<input type="text" name="txt_titular_tarjeta" class="cajatexto" id="txt_titular_tarjeta" maxlength="50"/><br>
						</div>
					</div>
					<div class="row">						
						<div class="col">
							<label for="txt_numero_tarjeta">Numero de tarjeta:</label><br>
							<input maxlength="16" type="number" name="txt_numero_tarjeta" class="cajatexto" id="txt_numero_tarjeta" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /><br>
						</div>
					</div>
					<div class="row">						
						<div class="col">
							<label for="txt_codigo_cvv">Código CVV:</label><br>
							<input placeholder="3 Digitos" maxlength="3"  type="number" name="txt_codigo_cvv" class="cajatexto" id="txt_codigo_cvv" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" /><br>
						</div>
					</div>
					<div class="row">						
						<div class="col">
							<label for="txt_vencimiento">Vencimiento:</label><br>
							<input type="date" name="txt_vencimiento" class="cajatexto" id="txt_vencimiento"/><br>
						</div>
					</div>
					<div class="col">
						<button type="submit" class="boton">Agregar método de pago</button>
					</div>
				<?php echo form_close();  ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>

	<!-- Ventana flotante para reporte de compras-->
    <div class="modal fade" id="reporte_suscripciones_modal" tabindex="-1" aria-labelledby="reporte_suscripciones_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reporte_suscripciones_modalLabel">Generar reporte de compras</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <?php echo form_open('Reporte/ReporteCompras/');?>   

                <div class="modal-body">                    
                    <div class="form-group">                        
                        <label for="txt_rangoFecha1">Fecha inicial:</label>
                        <input type="date" name="txt_rangoFecha1" class="cajatexto" id="txt_rangoFecha1"/>
                        <label for="txt_rangoFecha2">Fecha final:</label>
                        <input type="date" name="txt_rangoFecha2" class="cajatexto" id="txt_rangoFecha2"/>
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
<?php 
    }else {
        header("location: " . base_url());
    } 
?>