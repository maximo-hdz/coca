<?php
/*********************************************************************************
 *       Filename: Upload.php
 *       Proyecto Coca Consultores
 *       Adrian Bisiacchi 
 *       PHP 4.0 & Templates Escrito el 10/04/2002
 *********************************************************************************/

 include("./common.php");


 session_start();
check_security(2);

if(isset($binFile) && $binFile != "none") {

  $data=addslashes(fread(fopen($binFile, "r"),filesize($binFile)));
  $size=$binFile_size;
//  echo($size);
  if($size > 4000000){
	  header("Location: Listar Archivos_Can.php?id_candidato=" . get_param("id_candidato_nav") . "&Err=" . tourl("Error: El tama&ntilde;o del archivo excede 4MB."));}
   

  $SQL= "INSERT INTO ARCHIVOS (id_candidato,archivo,nombre,filetype,filesize) " .
        	"VALUES (" . get_param("id_candidato_nav") . ",'$data'," .
                        "'$binFile_name', '$binFile_type', '$binFile_size')";
//  echo($SQL);
  $db->query($SQL);
  header("Location: Listar Archivos_Can.php?id_candidato=" . get_param("id_candidato_nav"));

}


?>