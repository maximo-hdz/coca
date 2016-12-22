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
  $SQL="DELETE FROM RECORDATORIOS WHERE id_recordatorio=$id";
  $db->query($SQL);
}

header("Location: Listar Recordatorios.php?id_cliente_nav=" . get_param("id_cliente_nav"));
?>