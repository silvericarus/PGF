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
	<title>Datos de <?php echo $user["team"]["nombre"]?> | PGF</title>
	<link async rel="stylesheet" href="../../css/bulma.min.css">
    <link async rel="stylesheet" href="../../css/main.css">
    

    <script src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
</head>
<body>
	<!--Barra navegación-->
	<?php menu($user["rol"],"/"); ?>
 <!--Fin Barra navegación-->
<div class="columns">
    <div class="column">
        <figure class="image">
            <img src="../../img/strategy.svg" alt="Imagen temporal" width="300px">
        </figure>
        <h1 class="title has-text-centered"><?php echo $user["team"]["nivel"];?></h1>
    </div>
    <div class="column">
        <div class="tile is-parent">
            <div class="tile is-vertical">
                <div class="tile">
                    <div class="tile is-parent is-vertical">
                        <article class="tile is-child box">
                            <p class="title"><?php echo $user["team"]["nombre"];?></p>
                        </article>
                        <article class="tile is-child box">
                            <p class="title"><?php echo $user["team"]["pais"];?></p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="column">
        <div class="tile is-parent">
            <div class="tile is-vertical">
                <div class="tile">
                    <div class="tile is-parent is-vertical">
                        <article class="tile is-child box">
                            <p class="title"><?php echo $user["team"]["ciudad"];?></p>
                        </article>
                        <article class="tile is-child box">
                            <p class="title"><?php echo $user["team"]["nombre_estadio"];?></p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="column">
    <div class="tile is-parent">
            <div class="tile is-vertical">
                <div class="tile">
                    <div class="tile is-parent is-vertical">
                        <article class="tile is-child box">
                            <p class="title"><?php echo sacarDineroTotalFormateado($user["team"]["dinero_ahorrado"],$user["team"]["cent_ahorrado"])."€";?></p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<table class="table is-striped is-hoverable is-fullwidth">
  <thead>
    <tr>
        <th><abbr title="Orden">Ord</abbr></th>
        <th><abbr title="Posición">Pos</abbr></th>
        <th><abbr title="Nombre">Nom</abbr></th>
        <th><abbr title="Precio">€</abbr></th>
        <th><abbr title="Edad">Ed</abbr></th>
        <th><abbr title="Años de Contrato">AñCr</abbr></th>
        <th><abbr title="Tiempo restante en equipo">TiRe</abbr></th>
        <th><abbr title="Nacionalidad">Nac</abbr></th>
        <th>Opciones</th>
    </tr>
  </thead>
  <tfoot>
    <tr>
        <th><abbr title="Orden">Ord</abbr></th>
        <th><abbr title="Posición">Pos</abbr></th>
        <th><abbr title="Nombre">Nom</abbr></th>
        <th><abbr title="Precio">€</abbr></th>
        <th><abbr title="Edad">Ed</abbr></th>
        <th><abbr title="Años de Contrato">AñCr</abbr></th>
        <th><abbr title="Tiempo restante en equipo">TiRe</abbr></th>
        <th><abbr title="Nacionalidad">Nac</abbr></th>
        <th>Opciones</th>
    </tr>
  </tfoot>
  <tbody>
    <?php
        $con = conectarBD();
        $orden = 1;
        $sentencia = "SELECT j.nombre,j.posicion,j.precio,j.edad,j.fecha_contrato,j.años_contrato,j.nacionalidad FROM jugador j 
        WHERE j.id_equipo = ? ORDER BY precio desc, edad asc, nombre asc";
        $consulta = $con->prepare($sentencia);
        $consulta->bind_param("i",$user["team"]["id"]);
        $consulta->bind_result($nombre,$posicion,$precio,$edad,$fecha_contrato,$años_contrato,$nacionalidad);
        $consulta->execute();
        while($consulta->fetch()){
            echo "<tr>";
            echo "<td>".$orden."</td>";
            echo "<td>".$posicion."</td>";
            echo "<td>".$nombre."</td>";
            echo "<td>".sacarDineroTotalFormateado($precio,0)."</td>";
            echo "<td>".$edad."</td>";
            echo "<td>".$años_contrato."</td>";
            $arrayTiempoJugador = calcularTiempoRestanteJugador($años_contrato,$fecha_contrato);
            if($arrayTiempoJugador[1] == 1){
                echo "<td>".$arrayTiempoJugador[1]." año ".$arrayTiempoJugador[0]." meses</td>";
            }else if($arrayTiempoJugador[0] == 1){
                echo "<td>".$arrayTiempoJugador[1]." años ".$arrayTiempoJugador[0]." mes</td>";
            }else if($arrayTiempoJugador[0] == 1 && $arrayTiempoJugador[1] == 1){
                echo "<td>".$arrayTiempoJugador[1]." año ".$arrayTiempoJugador[0]." mes</td>";
            }else{
                echo "<td>".$arrayTiempoJugador[1]." años ".$arrayTiempoJugador[0]." meses</td>";
            }
            echo "<td>".$nacionalidad."</td>";
            //Si Ofertar es NO, poner el boton de Ofertar en Rojo, en caso contrario,
            //en verde.
            echo "<td>
                <div class='buttons has-addons is-centered'>
                    <a class='button is-fullwidth'>
                        <span class='icon is-small'>
                            <i class='fas fa-wrench'></i>
                            <span>Modificar</span>
                        </span>
                    </a>
                    <a class='button is-fullwidth is-danger'>
                    <span class='icon is-small'>
                        <i class='fas fa-hand-holding-usd'></i>
                        <span>Ofertar</span>
                    </span>
                    </a>
                </div>
                </td>";
            echo "</tr>";
            $orden++;
        }
        $consulta->close();
        $con->close();
    ?>  
  </tbody>
</table>        
<footer class="footer">
  <div class="content has-text-centered">
    <p>
      PGF fue hecho por Silver Icarus.
    </p>
  </div>
</footer>
</body>
</html>