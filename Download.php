<?php
/*********************************************************************************
 *       Filename: Download.php
 *       Proyecto Coca Consultores
 *       Adrian Bisiacchi 
 *       PHP 4.0 & Templates Escrito el 10/04/2002
 *********************************************************************************/

 include("./common.php");
 session_start();
check_security(2);

if(strlen(get_param("id_archivo"))) {
  $SQL="SELECT archivo,nombre,filesize,filetype from ARCHIVOS WHERE id_archivo=" . get_param("id_archivo");

  $db->query($SQL);
  $db->next_record();
  $data=$db->f("archivo");
  $name=$db->f("nombre");
  $size=$db->f("filesize");
  $type=$db->f("filetype");


  header("Content-type: $type");
  header("Content-length: $size");
  header("Content-Disposition: attachment; filename=$name");

//  header("Location: Listar Archivos.php?id_cliente_nav=" . get_param("id_cliente_nav"));
  echo($data);
}


?>