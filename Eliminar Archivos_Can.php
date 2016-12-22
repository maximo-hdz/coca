<?php
/*********************************************************************************
 *       Filename: Eliminar Recordatorios.php
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
  $SQL="DELETE FROM ARCHIVOS WHERE id_archivo=$id";
  $db->query($SQL);
}

header("Location: Listar Archivos_Can.php?id_candidato=" . get_param("id_candidato_nav"));
?>