<?php 

require_once('baseClass.php');
include('utils/funcUtils.php');

if (!validaCPF($_POST["cpf"])) {
	header("Location: erro.php");
}
else
{
	print_r($_POST);
}


?>