<?php
/*********************************************************************************
 *       Filename: Eliminar Facturas.php
 *       Proyecto Coca Consultores
 *       Adrian Bisiacchi
 *       PHP 4.0 & Templates Escrito el 12/04/2002
 *********************************************************************************/

include("./common.php");


session_start();
check_security(3);

$local=get_param("borrar_id");
foreach($local as $id)
{
  $SQL="Delete from facturas WHERE id_factura=" . $id;
  $db->query($SQL);
}

header("Location: Inicio Facturas.php?id_cliente_nav=" . get_param("id_cliente_nav"));
?>