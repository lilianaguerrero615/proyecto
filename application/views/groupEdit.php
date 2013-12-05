<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

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
				<td valign="top">Curso ID<span style="color:red;">*</span></td>
				<td><input type="text" name="course_id" class="text" value="<?php echo set_value('course_id',$this->form_data->course_id); ?>"/>
				<?php echo form_error('course_id'); ?>
				</td>
			</tr>
			<tr>
				<td valign="top">Quarter<span style="color:red;">*</span></td>
				<td><input type="text" name="quarter" class="text" value="<?php echo set_value('quarter',$this->form_data->quarter); ?>"/>
				<?php echo form_error('quarter'); ?>
				</td>
			</tr>
			<tr>
				<td valign="top">Profesor ID<span style="color:red;">*</span></td>
				<td><input type="text" name="professor_id" class="text" value="<?php echo set_value('professor_id',$this->form_data->professor_id); ?>"/>
				<?php echo form_error('professor_id'); ?>
				</td>
			</tr>
			<tr>
				<td valign="top">N Grupo<span style="color:red;">*</span></td>
				<td><input type="text" name="group_number" class="text" value="<?php echo set_value('group_number',$this->form_data->group_number); ?>"/>
				<?php echo form_error('group_number'); ?>
				</td>
			</tr>
			<tr>
				<td valign="top">Disponible(0 o 1)<span style="color:red;">*</span></td>
				<td><input type="text" name="enabled" class="text" value="<?php echo set_value('enabled',$this->form_data->enabled); ?>"/>
				<?php echo form_error('enabled'); ?>
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