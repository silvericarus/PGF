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
        $sentencia = "SELECT u.id,u.nombre_usuario,u.email,u.pass,u.rol,e.nombre,e.nivel,e.pais,e.ciudad,e.nombre_estadio,e.dinero_ahorrado,e.cent_ahorrado FROM usuario u,equipo e WHERE u.id_equipo = e.id AND u.id = $id";
        $data = mysqli_query($con,$sentencia);
        $row = mysqli_fetch_array($data,MYSQLI_ASSOC);
        while (!is_null($row)){
            $user["id"] = $row["id"];
            $user["nombre_usuario"] = $row["nombre_usuario"];
            $user["email"] = $row["email"];
            $user["pass"] = $row["pass"];
            $user["rol"] = $row["rol"];
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
?>