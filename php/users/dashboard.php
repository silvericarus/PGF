<?php session_start();
 ?>
<!DOCTYPE html>
<html>
<head>
	<?php 
		include "../helpers.php";
		$id = getId();
		$user = getUser($id);
	 ?>
	<title>Inicio del usuario <?php echo $user["nombre_usuario"]?> | PGF</title>
</head>
<body>

</body>
</html>