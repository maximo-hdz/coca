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

//  $result=@mysql_query($SQL,$db);

//  $data=@mysql_result($result, 0, "archivo");
//  $name=@mysql_result($result, 0, "nombre");
//  $size=@mysql_result($result, 0, "filesize");
//  $type=@mysql_result($result, 0, "filetype");

//  echo($name);
//  echo($size);
//  echo($type);

  header("Content-type: $type");
  header("Content-length: $size");
  header("Content-Disposition: attachment; filename=$name");
  header("Content-Description: PHP Generated Data");
  echo($data);
  

}


?>