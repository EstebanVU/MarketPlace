<div id="panel_app">
    <div class="box-header">
      	<h2 class="box-title lbls_" style="margin-left: 30px;">Agregando Usuario</h2>
      	<?php echo form_open('MarketPlace/index');?>
	    	<button type="submit" name="btn_return" id="btn_return" class="boton" title="Regresar">←</button>
	    <?php echo form_close();?>
    </div>
    <?php echo form_open('user/add'); ?>
  	<div id="edit_panel">

	  <label for="txt_tipo" class="control-label lbls_"><span class="text-danger">* </span>Tipo de usuario:</label>
		<div class="form-group">
			<select id="txt_tipo" name="txt_tipo" class="cajatexto" aria-label=".form-select-sm example">
				<!-- <option selected>Tipo de usuario</option> -->
				<option value="Cliente">Cliente</option>
				<option value="Tienda">Tienda</option>
			</select>
		</div>

		<label for="txt_correo" class="control-label lbls_"><span class="text-danger">* </span>Correo:</label>
		<div class="form-group">
			<input type="text" name="txt_correo" value="<?php echo $this->input->post('txt_correo'); ?>" class="cajatexto" id="txt_correo" />
			<span class="text-danger"><?php echo form_error('txt_correo');?></span>
		</div>

		<label for="txt_clave" class="control-label lbls_"><span class="text-danger">* </span>Contraseña:</label>
		<div class="form-group">
			<input type="password" name="txt_clave" value="<?php echo $this->input->post('txt_clave'); ?>" class="cajatexto" id="txt_clave" />
			<span class="text-danger"><?php echo form_error('txt_clave');?></span>
		</div>

		<label for="txt_nombre" class="control-label lbls_"><span class="text-danger">* </span>Nombre:</label>
		<div class="form-group">
			<input type="text" name="txt_nombre" value="<?php echo $this->input->post('txt_nombre'); ?>" class="cajatexto" id="txt_nombre" />
			<span class="text-danger"><?php echo form_error('txt_nombre');?></span>
		</div>

		<label for="txt_cedula" class="control-label lbls_"><span class="text-danger">* </span>Cedula:</label>
		<div class="form-group">
			<input type="text" name="txt_cedula" value="<?php echo $this->input->post('txt_cedula'); ?>" class="cajatexto" id="txt_cedula" />
			<span class="text-danger"><?php echo form_error('txt_cedula');?></span>
		</div>

		<label for="txt_telefono" class="control-label lbls_"><span class="text-danger">* </span>Telefono:</label>
		<div class="form-group">
			<input type="text" name="txt_telefono" value="<?php echo $this->input->post('txt_telefono'); ?>" class="cajatexto" id="txt_telefono" />
			<span class="text-danger"><?php echo form_error('txt_telefono');?></span>
		</div>

		<label for="txt_pais" class="control-label lbls_"><span class="text-danger">* </span>Pais:</label>
		<div class="form-group">
			<input type="text" name="txt_pais" value="<?php echo $this->input->post('txt_pais'); ?>" class="cajatexto" id="txt_pais" />
			<span class="text-danger"><?php echo form_error('txt_pais');?></span>
		</div>

		<label for="txt_provincia" class="control-label lbls_"><span class="text-danger">* </span>Provincia:</label>
		<div class="form-group">
			<input type="text" name="txt_provincia" value="<?php echo $this->input->post('txt_provincia'); ?>" class="cajatexto" id="txt_provincia" />
			<span class="text-danger"><?php echo form_error('txt_provincia');?></span>
		</div>
		<br>
	  	<div class="box-footer">
	    	<button type="submit" class="boton">Guardar</button>
	  	</div>
    <?php echo form_close(); ?>
	</div>
</div>