<?php
/*********************************************************************************
 *       Filename: Login.php
 *       Proyecto Dennis Crowley
 *       Victor Medrano - NPS México - Colabora Adrian Bisiacchi
 *       PHP 4.0 & Templates Escrito el 04/03/2002
 *********************************************************************************/

//-------------------------------
// Login CustomIncludes begin

include ("./common.php");

// Login CustomIncludes end
//-------------------------------

session_start();

//===============================
// Save Page and File Name available into variables
//-------------------------------
$filename = "barra.php";
$template_filename = "barra.htm";
//===============================


//===============================
// Display page
//-------------------------------
// Load HTML template for this page
//-------------------------------
$tpl = new Template($app_path);
$tpl->load_file($template_filename, "main");

//echo(get_session("UserID"));

if(!security_info(3))
{
 $tpl->set_var("EmpleadosBlock", "");
 if(!security_info(2))
 {
  $tpl->set_var("CarrerasBlock", "");
  $tpl->set_var("FuentesBlock", "");
  $tpl->set_var("ClientesBlock", "");
  if(!get_session("UserID"))
  {
   $tpl->set_var("CandidatosBlock", "");
  }
 }
}

$tpl->parse("BarraBlock",false);
$tpl->pparse("main",false);

?>