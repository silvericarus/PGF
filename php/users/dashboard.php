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
	<link async rel="stylesheet" href="../../css/bulma.min.css">
    <link async rel="stylesheet" href="../../css/main.css">
    

    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>
<body>
	<!--Barra navegación-->
	<?php menu($user["rol"],"/"); ?>
 <!--Fin Barra navegación-->
<div class="columns">
	<div class="column is-one-quarter">
		<div class="card">
  			<div class="card-image">
    			<figure class="image is-4by3">
      				<img src="https://bulma.io/images/placeholders/1280x960.png" alt="Placeholder image">
    			</figure>
  			</div>
  			<div class="card-content">
    			<div class="media">
      				<div class="media-left">
        				<figure class="image is-48x48">
          					<img src="https://bulma.io/images/placeholders/96x96.png" alt="Placeholder image">
        				</figure>
      				</div>
      			<div class="media-content">
        			<p class="title is-4"><?php echo $user["nombre_usuario"];?></p>
      			</div>
    		</div>

    		<div class="content">
    			El equipo <?php echo $user["team"]["nombre"]; ?> es de la ciudad de <?php echo $user["team"]["ciudad"]; ?>
    			y tiene una capacidad económica de <?php echo sacarDineroTotalFormateado($user["team"]["dinero_ahorrado"],$user["team"]["cent_ahorrado"]);?>.
    		</div>
  			</div>
		</div>
		<!--Ofertas-->
		<div class="card">
  			<div class="card-image">
    			<figure class="image is-4by3">
      				<img src="https://bulma.io/images/placeholders/1280x960.png" alt="Placeholder image">
    			</figure>
  			</div>
  			<div class="card-content">
    			<div class="media">
      				<div class="media-left">
        				<figure class="image is-48x48">
          					<img src="https://bulma.io/images/placeholders/96x96.png" alt="Placeholder image">
        				</figure>
      				</div>
      			<div class="media-content">
        			<p class="title is-4"><?php echo $user["nombre_usuario"];?></p>
      			</div>
    		</div>

    		<div class="content">
    			El equipo <?php echo $user["team"]["nombre"]; ?> es de la ciudad de <?php echo $user["team"]["ciudad"]; ?>
    			y tiene una capacidad económica de <?php echo sacarDineroTotalFormateado($user["team"]["dinero_ahorrado"],$user["team"]["cent_ahorrado"]);?>.
    		</div>
  			</div>
		</div>
	</div>
	<div class="column">
		<section class="hero" style="width: 800px; 
		padding-right: 10px;">
  			
			  <div class="hero-body has-text-centered is-halfheight">
			  	<fieldset class="box">
  					<legend class="label has-text-centered">Próximos partidos de <?php echo $user["team"]["nombre"]; ?>.</legend>
    			<?php 
    				$con = conectarBD();
    				$ciudadPartido;
    				$fechaPartido;
    				$sentencia = "SELECT p.ciudad,p.fecha FROM equipo e, partido p,liga l,participa,compone,usuario u WHERE p.id = compone.id_partido and compone.id_liga = l.id and l.id = participa.id_liga and participa.id_equipo = e.id and u.id_equipo = e.id and u.id = ? order by p.fecha asc LIMIT 2";

    				$consulta = $con->prepare($sentencia);
    				$consulta->bind_param("i",$user["id"]);
    				$consulta->bind_result($ciudadPartido,$fechaPartido);
    				$consulta->execute();
    				while ($consulta->fetch()) {
						echo "<div class=\"card\">
                          <div class=\"card-image\">
                            <figure class=\"image\">
                              <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" 
                              height ='100%' weight='100%' viewBox=\"0 0 24 24\" stroke=\"currentColor\">
  <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z\" />
</svg>
                            </figure>
                          </div>";
                          
                            echo "
                          <div class=\"card-content\">
                            <div class=\"content\" style='text-align: center'>
                              $ciudadPartido <br>
                              $fechaPartido
                            </div>
                          </div>
                          <footer class='card-footer is-justify-content-center'>
    							<a class='button is-info' href=''>Más información</a>
  							</footer>
                        </div>";
    				}
    				$consulta->close();
    			 ?>
				</fieldset>
			  </div>
		</section>
		<section class="hero" style="width: 800px;">
  			<div class="hero-body has-text-centered is-halfheight">
			  <fieldset class="box">
  				<legend class="label has-text-centered">Próximos partidos con apuestas.</legend>
    			<?php 
    				$fechaPartido1;
    				$ciudadPartido1;
    				$sentencia1 = "SELECT p.fecha,p.ciudad FROM apuesta a,partido p,hace,usuario u WHERE a.p_influye = p.id AND u.id = hace.id_usuario AND a.id = hace.id_apuesta AND u.id = ? order by p.fecha asc LIMIT 2";

    				$consulta = $con->prepare($sentencia1);
    				$consulta->bind_param("i",$user["id"]);
    				$consulta->bind_result($fechaPartido1,$ciudadPartido1);
    				$consulta->execute();

    				while ($consulta->fetch()) {
						echo "<div class=\"card\">
                          <div class=\"card-image\">
                            <figure class=\"image\">
                              <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" 
                              height ='100%' weight='100%' viewBox=\"0 0 24 24\" stroke=\"currentColor\">
  <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z\" />
</svg>
                            </figure>
                          </div>";
                          
                            echo "
                          <div class=\"card-content\">
                            <div class=\"content\" style='text-align: center'>
                              $ciudadPartido1 <br>
                              $fechaPartido1 <br>
                            </div>
                          </div>
                          <footer class='card-footer is-justify-content-center'>
    							<a class='button is-info' href=''>Más información</a>
  							</footer>
                        </div>";
    				}
    			 ?>
				 </fieldset>
  			</div>
		</section>
	</div>
</div>
<footer class="footer">
  <div class="content has-text-centered">
    <p>
      PGF fue hecho por Silver Icarus.
    </p>
  </div>
</footer>
</body>
</html>