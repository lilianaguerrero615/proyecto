<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title></title>

<link href="<?php echo base_url(); ?>res/css/style.css" rel="stylesheet" type="text/css" />

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
		<div class="data">
		<table>
			<tr>
				<td width="30%">ID</td>
				<td><?php echo $group->id; ?></td>
			</tr>
			<tr>
				<td valign="top">Curso ID</td>
				<td><?php echo $group->course_id; ?></td>
			</tr>
			<tr>
				<td valign="top">Quarter</td>
				<td><?php echo $group->quarter; ?></td>
			</tr>
			<tr>
				<td valign="top">Profesor ID</td>
				<td><?php echo $group->professor_id; ?></td>
			</tr>
			<tr>
				<td valign="top">N Grupo</td>
				<td><?php echo $group->group_number; ?></td>
			</tr>
			<tr>
				<td valign="top">Disponible</td>
				<td><?php echo $group->enabled; ?></td>
			</tr>
		</table>
		</div>
		<br />
		<?php echo $link_back; ?>
	</div>
</body>
</html>