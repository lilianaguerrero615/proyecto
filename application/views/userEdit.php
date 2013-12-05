<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title></title>

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
				<td valign="top">Cedula<span style="color:red;">*</span></td>
				<td><input type="text" name="document_number" class="text" value="<?php echo set_value('document_number',$this->form_data->document_number); ?>"/>
				<?php echo form_error('document_number'); ?>
				</td>
			</tr>
			<tr>
				<td valign="top">Contrase&ntilde;a<span style="color:red;">*</span></td>
				<td><input type="password" name="password" class="text" value="<?php echo set_value('password',$this->form_data->password); ?>"/>
				<?php echo form_error('password'); ?>
				</td>
			</tr>
			<tr>
				<td valign="top">Tipo<span style="color:red;">*</span></td>
				<td><input type="text" name="role" class="text" value="<?php echo set_value('role',$this->form_data->role); ?>"/>
				<?php echo form_error('role'); ?>
				</td>
			</tr>
			<tr>
				<td valign="top">Status<span style="color:red;">*</span></td>
				<td><input type="text" name="status" class="text" value="<?php echo set_value('status',$this->form_data->status); ?>"/>
				<?php echo form_error('status'); ?>
				</td>
			</tr>
			<tr>
				<td valign="top">Administrador<span style="color:red;">*</span></td>
				<td><input type="text" name="admin" class="text" value="<?php echo set_value('admin',$this->form_data->admin); ?>"/>
				<?php echo form_error('admin'); ?>
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