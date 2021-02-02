<?php session_start();
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
	<title>Iniciar Sesión | PGF</title>
    <link async rel="stylesheet" href="css/bulma.min.css">
    <link async rel="stylesheet" href="css/main.css">
    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>
<body>
	<?php
	include "php/helpers.php";
	if (isset($_POST["Enviar"])) {
		$user = $_POST["user"];
		$pass = $_POST["pass"];
		$idUser;
		$rol;
		if (isset($_POST["rememberMe"])) {
			$rememberMe = $_POST["rememberMe"];
		}
		

		$con = conectarBD();

		$sentencia = "SELECT id,rol from usuario WHERE nombre_usuario = ? AND pass = ?";

		$consulta = $con->prepare($sentencia);
		$consulta->bind_param("ss",$user,$pass);
		$consulta->bind_result($idUser,$rol);
		$consulta->execute();
		$consulta->store_result();
		$consulta->fetch();
		if($consulta->affected_rows == 0){
			$consulta->close();
			$con->close();
			header("Location:./login.php?loginerror=1");
		}else{
			$_SESSION["sessionid"] = $idUser;
			if (!empty($rememberMe)) {
                $datossession = session_encode();

                setcookie("sessionid", $datossession, time() + 31557600, "/");
            }
            $consulta->close();
			$con->close();
			if($rol == "user"){
				header("Location:./php/users/dashboard.php");
			}else{
				header("Location:./php/admins/dashboard.php");
			}
		}

	}
	?>
	<?php if (isset($_GET["loginerror"])&&!isset($_POST["Enviar"])):?>
		<div class="notification is-danger">
			Alguno de los datos introducidos no era adecuado y no ha sido posible el registro. Inténtalo de nuevo.
		</div>
	<?php endif; ?>
	<section class="hero is-fullheight">
		<div class="hero-body">
			<div class="container">
				<div class="columns is-centered">
					<form action="#" class="box" method="post" name="register">
						<div class="column">
							<div class="field has-text-centered">
                                <img src="img/strategy.svg" alt="Logo Nombre" width="167px">
                            </div>
                            <div class="field">
                                <label class="label">Nombre de Usuario</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="text" placeholder="Nombre de usuario" name="user" required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                            </div>
                        
                            <div class="field">
                                <label class="label">Contraseña</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="password" placeholder="Contraseña" name="pass" required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-key"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="checkbox">
                                    <input type="checkbox" name="rememberMe">
                                    Recuérdame
                                </label>
                            </div>
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