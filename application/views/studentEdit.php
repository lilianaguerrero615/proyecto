<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>Estudiante</title>

<link href="<?php echo base_url(); ?>res/css/style.css" rel="stylesheet" type="text/css" />

<link href="<?php echo base_url(); ?>res/css/calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>res/js/calendar.js"></script>

</head>
<body>
	<div class="logo">
		<img src="<?php echo base_url(); ?>res/logo.png" />
	</div>
	<div class="nav">
		<ul>
		<li><a href="<?=site_url('/student');?>">Estudiantes</a></li>
		<li><a href="<?=site_url('/professor');?>">Profesores</a></li>
		<li><a href="<?=site_url('/classroom');?>">Aulas</a></li>
		<li><a href="<?=site_url('/course');?>">Cursos</a></li>
		<li><a href="<?=site_url('/group');?>">Grupos</a></li>
		<li><a href="<?=site_url('/user');?>">Usuarios</a></li>
		</ul>
	</div>
	<div class="content">
		<h1><?php echo $title; ?></h1>
		<?php echo $message; ?>
		<form method="post" action="<?php echo $action; ?>">
		<div class="data">
		<table>
			<tr>
				<td width="30%">ID</td>
				<td><input type="text" name="id" disabled="disable" class="text" value="<?php echo set_value('id'); ?>"/></td>
				<input type="hidden" name="id" value="<?php echo set_value('id',$this->form_data->id); ?>"/>
			</tr>
			<tr>
				<td valign="top">Nombre<span style="color:red;">*</span></td>
				<td><input type="text" name="first_name" class="text" value="<?php echo set_value('first_name',$this->form_data->first_name); ?>"/>
				<?php echo form_error('first_name'); ?>
				</td>
			</tr>
			<tr>
				<td valign="top">Apellidos<span style="color:red;">*</span></td>
				<td><input type="text" name="last_name" class="text" value="<?php echo set_value('last_name',$this->form_data->last_name); ?>"/>
				<?php echo form_error('last_name'); ?>
				</td>
			</tr>
			<tr>
				<td valign="top">Cedula<span style="color:red;">*</span></td>
				<td><input type="text" name="document_number" class="text" value="<?php echo set_value('document_number',$this->form_data->document_number); ?>"/>
				<?php echo form_error('document_number'); ?>
				</td>
			</tr>
			<tr>
				<td valign="top">Correo<span style="color:red;">*</span></td>
				<td><input type="text" name="email" class="text" value="<?php echo set_value('email',$this->form_data->email); ?>"/>
				<?php echo form_error('email'); ?>
				</td>
			</tr>

			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" value="Guardar"/></td>
			</tr>
		</table>
		</div>
		</form>
		<br />
		<?php echo $link_back; ?>
	</div>
</body>
</html>