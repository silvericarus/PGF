<?php
	function conectarBD(){
		$con = mysqli_connect("localhost","root","","pgf");

        mysqli_set_charset($con,"utf8");

        return $con;
	}

	/**
     * [checkUser Comprueba si un user existe ya en la base de datos para que no se repita]
     * @param  [string] $user [El user a comprobar]
     * @return [boolean]       [true si es válido y false si no lo es]
     */
    function checkUser($user){
        $conector = conectarBD();
        $consulta = "SELECT nombre_usuario from usuario;";
        $datos = mysqli_query($conector,$consulta);
        $resultado = mysqli_fetch_array($datos,MYSQLI_ASSOC);
        while(!is_null($resultado)){
            if($resultado["nombre_usuario"]==$user){
                mysqli_close($conector);
                return false;
            }else{
                $resultado = mysqli_fetch_array($datos,MYSQLI_ASSOC);
            }
        }
        mysqli_close($conector);
        return true;
    }

    function getUser($id){
        $user = array();
        $team = array();
        $con = conectarBD();
        $sentencia = "SELECT u.id,u.nombre_usuario,u.email,u.pass,u.rol,e.id teamid,e.nombre,e.nivel,e.pais,e.ciudad,e.nombre_estadio,e.dinero_ahorrado,e.cent_ahorrado FROM usuario u,equipo e WHERE u.id_equipo = e.id AND u.id = $id";
        $data = mysqli_query($con,$sentencia);
        $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
        while (!is_null($row)){
            $user["id"] = $row["id"];
            $user["nombre_usuario"] = $row["nombre_usuario"];
            $user["email"] = $row["email"];
            $user["pass"] = $row["pass"];
            $user["rol"] = $row["rol"];
            $team["id"] = $row["teamid"];
            $team["nombre"] = $row["nombre"];
            $team["nivel"] = $row["nivel"];
            $team["pais"] = $row["pais"];
            $team["ciudad"] = $row["ciudad"];
            $team["nombre_estadio"] = $row["nombre_estadio"];
            $team["dinero_ahorrado"] = $row["dinero_ahorrado"];
            $team["cent_ahorrado"] = $row["cent_ahorrado"];
            $user["team"] = $team;
            $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
        }
        return $user;
    }

    function getId(){
        if (isset($_SESSION["sessionid"])) {
            return $_SESSION["sessionid"];
        }else if (isset($_COOKIE["sessionid"])) {
            session_decode($_COOKIE["sessionid"]);
            return $_SESSION["sessionid"];
        }else{
            return null;
        }
    }

    function checkID($id,$mode){
        if ($mode == "nologin"){
            if ($id != null){
                $con = conectarBD();
                $consulta = "SELECT rol from usuario WHERE id = '$id'";
                $data = mysqli_query($con,$consulta);
                $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                while(!is_null($row)) {
                    $role = $row["rol"];
                    if ($role == "user"){
                        header("Location:./php/users/dashboard.php");
                    }else{
                        header("Location:./php/admin/dashboard.php");
                    }
                    $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
                }
            }
        }else{
            $con = conectarBD();
            $consulta = "SELECT rol from usuario WHERE id = '$id'";
            $data = mysqli_query($con,$consulta);
            $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
            while(!is_null($row)) {
                $role = $row["rol"];
                if ($role==$mode){
                    return 1;
                }else{
                    return 0;
                }
                $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
            }
        }
    }

    function sacarDineroTotalFormateado($dinero,$centimos){
        $dineroTotal = $dinero.",".$centimos;
        settype($dineroTotal,"float");
        return number_format($dineroTotal,2,",",".");
    }

    function menu($rol,$div){
        switch ($rol) {
            case 'user':
                switch ($div) {
                    case '/':
                        echo "  <nav class=\"navbar is-pgf-primary\" role=\"navigation\" aria-label=\"main navigation\">
        <div class=\"navbar-brand\">
            <a class=\"navbar-item\" href=\"./dashboard.php\">
                <img src=\"../../img/strategy.svg\" width=\"112\" height=\"26\" alt=\"Logo por ahora\">
            </a>

            <a role=\"button\" class=\"navbar-burger burger\" aria-label=\"menu\" aria-expanded=\"false\" data-target=\"navbarBasicExample\">
                <span aria-hidden=\"true\"></span>
                <span aria-hidden=\"true\"></span>
                <span aria-hidden=\"true\"></span>
            </a>
        </div>

        <div id=\"navbarBasicExample\" class=\"navbar-menu\">
            <div class=\"navbar-start\">
                <a class=\"navbar-item\" href=\"./teamData.php\">
                    Zona Equipo
                </a>

                <a class=\"navbar-item\" href=\"\">
                    Zona Ligas
                </a>

                <a class=\"navbar-item\" href=\"\">
                    Zona Económica
                </a>
            </div>
            <div class=\"navbar-end\">
                <div class=\"navbar-item\">
                    <div class=\"buttons\">
                        <a class=\"button is-pgf-primary-dark\" href=\"../cerrarSesion.php\">
                            Cerrar sesión
                        </a>
                        <a class=\"button is-pgf-primary-dark\" href=\"./user_config.php\">
                            <i class=\"fas fa-cog\"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>";
                        break;
                    
                    default:
                        # code...
                        break;
                }
                break;
        }
    }

    function calcularTiempoRestanteJugador($años_contrato,$fecha_contrato){
        $fecha_contrato_datetime = new Datetime($fecha_contrato);
        $fecha_final = $fecha_contrato_datetime->add(date_interval_create_from_date_string($años_contrato."years"));
        $fecha_actual = new Datetime(date("Y-m-d")); 
        $interval=$fecha_final->diff($fecha_actual);

        # obtenemos la diferencia en meses

        $intervalMeses=$interval->format("%m");

        # obtenemos la diferencia en años y la multiplicamos por 12 para tener los meses

        $intervalAnos = $interval->format("%y")*12;
        $intervalArray[0] = $intervalAnos;
        $intervalArray[1] = $intervalMeses;

        return $intervalArray;

    }
?>