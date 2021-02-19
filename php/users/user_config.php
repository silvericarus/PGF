<?php session_start();
 ?>
<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8">
	<title>Modificar datos usuario | PGF</title>
	<link async rel="stylesheet" href="../../css/bulma.min.css">
	<link async rel="stylesheet" href="../../css/main.css">
	<script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>

<body>
	<?php
	include "../../php/helpers.php";
	$con = conectarBD();
	$id = getId();
	$user = getUser($id);
	menu($user["rol"],"/");

	$sentencia = "SELECT u.nombre_usuario, u.email, e.nombre, e.ciudad, e.nombre_estadio FROM equipo e,usuario u WHERE e.id = u.id_equipo AND u.id = ?";
	$consulta = $con->prepare($sentencia);
	$consulta->bind_param("i",$user["id"]);
	$consulta->bind_result($nombre_usuario,$email,$nombre_equipo,$ciudad,$nombre_estadio);
	$consulta->execute();
	while ($consulta->fetch()) {
		
	}
	$consulta->close();
	
	if (isset($_POST["Enviar"])) {

		$email = $_POST["email"];
		
		$pass = $_POST["pass"] != ""?$_POST["pass"]:NULL;

		$nombre_equipo = $_POST["nombre_equipo"];
		$ciudad = $_POST["city"];
		$nombre_estadio = $_POST["stadium"];

		if(is_null($pass)){
			$sentencia = "UPDATE usuario SET email = ? WHERE id = ?";
			$consulta = $con->prepare($sentencia);
			$consulta->bind_param("si",$email,$user["id"]);
		}else{
			$sentencia = "UPDATE usuario SET email = ?, pass = ? WHERE id = ?";
			$consulta = $con->prepare($sentencia);
			$consulta->bind_param("ssi",$email,$pass,$user["id"]);
		}
		
		$consulta->store_result();
		$consulta->execute();
		$affectedRowsTmp = $consulta->affected_rows;
		$consulta->close();
		$sentencia1 = "UPDATE equipo SET nombre = ? , ciudad = ? , nombre_estadio = ? WHERE id = ?";
		$consulta1 = $con->prepare($sentencia1);
		$consulta1->bind_param("sssi",$nombre_equipo,$ciudad,$nombre_estadio,$user["team"]["id"]);
		$consulta1->store_result();
		$consulta1->execute();
		if ($affectedRowsTmp==0 && $consulta1->affected_rows==0) {
			$consulta1->close();
			$con->close();
			header("Location:./user_config.php?updateerror=1");
		}else{
			$consulta1->close();
			$con->close();
			echo "yes";
			header("Location:./user_config.php?correctupdate=1");
		}
	}
	
	?>
	<?php if (isset($_GET["updateerror"])&&!isset($_POST["Enviar"])):?>
	<div class="notification is-danger">
		Alguno de los datos introducidos no era adecuado y no ha sido posible el registro. Inténtalo de nuevo.
	</div>
	<?php elseif(isset($_GET["correctupdate"])&&!isset($_POST["Enviar"])):?>
	<div class="notification is-success">
		Se han modificado adecuadamente los datos introducidos.
	</div>
	<?php endif; ?>

	<section class="hero is-fullheight">
		<div class="hero-body">
			<div class="container">
				<div class="columns is-centered">
					<form action="#" class="box" method="post" name="editProfile">
						<div class="column">
							<div class="field has-text-centered">
								<img src="../../img/strategy.svg" alt="Logo Nombre" width="167px">
							</div>
							<fieldset class="box">
								<legend class="label has-text-centered">Usuario</legend>
								<div class="field">
									<label class="label">Correo Electrónico</label>
									<div class="control has-icons-left">
										<input class="input" type="email" value='<?php echo $email; ?>' name="email">
										<span class="icon is-small is-left">
											<i class="fas fa-envelope"></i>
										</span>
									</div>
								</div>
								<div class="field">
									<label class="label">Contraseña</label>
									<div class="control has-icons-left">
										<input class="input" type="password" placeholder="Contraseña" name="pass">
										<span class="icon is-small is-left">
											<i class="fas fa-key"></i>
										</span>
									</div>
								</div>
							</fieldset>
							<fieldset class="box">
								<legend class="label has-text-centered">Equipo</legend>
								<div class="field">
									<label class="label">Nombre</label>
									<div class="control has-icons-left">
										<input class="input" type="text" value='<?php echo $nombre_equipo; ?>'
											name="nombre_equipo">
										<span class="icon is-small is-left">
											<i class="fas fa-users"></i>
										</span>
									</div>
								</div>
								<div class="field">
									<label class="label">Ciudad</label>
									<div class="control has-icons-left">
										<input class="input" type="text" value='<?php echo $ciudad; ?>' name="city">
										<span class="icon is-small is-left">
											<i class="fas fa-city"></i>
										</span>
									</div>
								</div>
								<div class="field">
									<label class="label">Nombre Estadio</label>
									<div class="control has-icons-left">
										<input class="input" type="text" value='<?php echo $nombre_estadio; ?>'
											name="stadium">
										<span class="icon is-small is-left">
											<i class="fas fa-building"></i>
										</span>
									</div>
								</div>
							</fieldset>
							<div class="field">
								<input class="button is-pgf-primary-dark" name="Enviar" value="Enviar" type="submit">
							</div>
						</div>
				</div>
				</form>
			</div>
		</div>
		</div>
	</section>
</body>

</html>