<?php session_start();
 ?>
<?php 
	include "php/helpers.php";
    $con = conectarBD();
    $id = getId();
    if(isset($id)){
        $user = getUser($id);
        checkID($user["id"],"nologin");
    }
    
 ?>
 <!DOCTYPE html>
 <html lang="es">
 <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <!--<link rel="icon" href="img/favicon.png" type="image/png" sizes="32x32">-->
    <title>Bienvenido | PGF</title>
    <link async rel="stylesheet" href="css/bulma.min.css">
    <link async rel="stylesheet" href="css/main.css">
    

    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
 </head>
 <body>
 <!--Barra navegación-->
 	<nav class="navbar is-pgf-primary" role="navigation" aria-label="main navigation">
 		<div class="navbar-brand">
            <a class="navbar-item" href="./landingpage.php">
                <img src="img/strategy.svg" width="112" height="26" alt="Logo por ahora">
            </a>

            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="#aboutus">
                    Sobre Nosotros
                </a>

                <a class="navbar-item" href="#nextmatches">
                    Próximos partidos
                </a>

                <a class="navbar-item" href="#bestplayers">
                    Mejores jugadores
                </a>
            </div>
            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="buttons">
                        <a class="button is-pgf-primary-dark" href="register.php">
                            Registrarse
                        </a>
                        <a class="button is-pgf-primary-dark" href="login.php">
                            Iniciar sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
 	</nav>
 <!--Fin Barra navegación-->
 <!--Sección Sobre Nosotros-->
<section class="hero is-medium is-bold">
    <div class="hero-body">
        <div class="container is-opaque">
			<h1 class="title">
				<span id="aboutus">Sobre nosotros</span>
			</h1>
			<h2 class="subtitle">
                    Hay muchos tipos de fotógrafos, casi tantos como de objetivos, o de cámaras en el mercado. Éste es vuestro punto de encuentro. Conoced nuevos compañeros que comparten vuestra pasión y quizá vuestros gustos. Participad en numerosas competiciones de fotografía organizadas por nuestra comunidad y alzaos con la victoria mientras subid por las escalera de la fama. Conoced en que están trabajando las personas que os interesan, y también contadle a todos los que le interesáis qué es de vuestra vida. Desde los amantes de los felinos aficionados a la fotografía de sus amigos peludos hasta los dueños de un estudio profesional en búsqueda de ampliar sus horizontes con nuevas ideas y proyectos, todos tienen cabida en la comunidad de Shutter. Así que...<strong>¿porque no te unes?</strong>
			</h2>
        </div>
    </div>
 </section>
<!--Sección últimos partidos-->
<?php
	$conector = conectarBD();


	$sentencia = "SELECT e.nombre,p.ciudad,p.fecha FROM equipo e, partido p,liga l,participa,compone WHERE p.id = compone.id_partido and compone.id_liga = l.id and l.id = participa.id_liga and participa.id_equipo = e.id and p.e_gana IS NULL order by p.fecha asc LIMIT 6";
	$consulta = $conector->prepare($sentencia);
	$consulta->bind_result($nombre,$ciudad,$fecha);
	$consulta->execute();
?>
	<section class="hero">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    <span id="nextmatches">Próximos partidos</span>
                </h1>
				<div class="columns">
				<?php 
                    $fechaAnterior = null;
                    $nombreAnterior = null;
					while ($consulta->fetch()) {
						if($fechaAnterior == $fecha){
                        echo "<div class='column'>";
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
                              $nombreAnterior - $nombre <br> $ciudad
                              $fecha
                            </div>
                          </div>
                        </div>
                        </div>";
                        }
                        $fechaAnterior = $fecha;
                        $nombreAnterior = $nombre;
					}
					$consulta->close();
				 	?>
				</div>
            </div>
        </div>
    </section>
    <!--Sección Mejores futbolistas-->
    <?php


    $sentencia = "SELECT nombre FROM jugador ORDER BY precio desc, edad asc, nombre asc LIMIT 4";
    $consulta = $conector->prepare($sentencia);
    $consulta->bind_result($nombre);
    $consulta->execute();
?>
    <section class="hero">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    <span id="bestplayers">Mejores Jugadores</span>
                </h1>
                <div class="columns">
                    <?php
                        $orden = 1;
                        while ($consulta->fetch()) {
                            echo "<div class='column'>";
                            echo "<div class=\"card\">
                          <div class=\"card-image\">
                            <figure class=\"image\">
                                <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\">
                                    <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\" />
                                </svg>
                                </figure>
                          </div>";
                          echo "
                          <div class=\"card-content\">
                            <div class=\"content\" style='text-align: center'>
                              ".$orden."º: $nombre
                            </div>
                          </div>
                        </div>
                        </div>";
                        $orden++;
                        
                        }
                        $consulta->close();
                        $conector->close();
                    ?>
                    </div>
            </div>
        </div>
    </section>
 </body>
 </html>