<?php
/*********************************************************************************
 *       Filename: Eliminar Empleados.php
 *       Proyecto Coca Consultores
 *       Adrian Bisiacchi
 *       PHP 4.0 & Templates Escrito el 12/04/2002
 *********************************************************************************/

include("./common.php");


 session_start();
check_security(2);

$local=get_param("borrar_id");
foreach($local as $id)
{
  $SQL="DELETE FROM REFERENCIAS WHERE id_candidato=$id";
  $db->query($SQL);

  $SQL="DELETE FROM IDIOMAS WHERE id_candidato=$id";
  $db->query($SQL);

  $SQL="DELETE FROM CANDIDATOS WHERE id_candidato=$id";
  $db->query($SQL);
}

header("Location: Inicio Candidatos.php");
?>