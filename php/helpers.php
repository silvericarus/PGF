<?php
	function conectarBD(){
		$con = mysqli_connect("localhost","root","","pgf");

        mysqli_set_charset($con,"utf8");

        return $con;
	}
?>