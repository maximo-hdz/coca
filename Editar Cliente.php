<?php
/*********************************************************************************
 *       Filename: Insertar Cliente.php
 *       Proyecto COCA Consultores
 *       Adrian Bisiacchi
 *       PHP 4.0 & Templates Escrito el 08/04/2002
 *********************************************************************************/

//-------------------------------
// Insertar cliente CustomIncludes begin

include ("./common.php");

// Insertar cliente CustomIncludes end
//-------------------------------

session_start();

//===============================
// Save Page and File Name available into variables
//-------------------------------
$filename = "Editar Cliente.php";
$template_filename = "Editar Cliente.html";
//===============================



//===============================
// Insertar cliente PageSecurity begin
check_security(2);
// Insertar cliente PageSecurity end
//===============================

//===============================
// Insertar cliente Open Event begin
// Insertar cliente Open Event end
//===============================

//===============================
// Insertar cliente OpenAnyPage Event start
// Insertar cliente OpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

// Insertar cliente Show begin

//===============================
// Perform the form's action
//-------------------------------
// Initialize error variables
//-------------------------------
$sClienteErr = "";

//-------------------------------
// Select the FormAction
//-------------------------------
switch ($sForm) {
  case "Cliente":
    cliente_action($sAction);
  break;
}
//===============================

//===============================
// Display page
//-------------------------------
// Load HTML template for this page
//-------------------------------
$tpl = new Template($app_path);
$tpl->load_file($template_filename, "main");
//-------------------------------
// Load HTML template of Header and Footer
//-------------------------------
$tpl->load_file($header_filename, "Header");
//-------------------------------
$tpl->set_var("FileName", $filename);



//-------------------------------
// Step through each form
//-------------------------------
Cliente_show();

//-------------------------------
// Process page templates
//-------------------------------
//$tpl->parse("Header", false);
//-------------------------------
// Output the page to the browser
//-------------------------------
   $tpl->pparse("main", false);
// Insertar cliente Show end

//===============================
// Insertar cliente Close Event begin
// Insertar cliente Close Event end
//===============================
//********************************************************************************


//===============================
// Action of the Record Form
//-------------------------------
function Cliente_action($sAction)
{
//-------------------------------
// Initialize variables  
//-------------------------------
  global $db;
  global $tpl;
  global $sForm;
  global $sClienteErr;
  $bExecSQL = true;
  $sActionFileName = "";
  $sWhere = "";
  $bErr = false;
  $pPKcli_id = "";
  $fldnombre = "";
//-------------------------------

//-------------------------------
// Cliente Action begin
//-------------------------------
  $sActionFileName = "Inicio clientes.php";

//-------------------------------
// CANCEL action
//-------------------------------
  if($sAction == "cancel")
  {

//-------------------------------
// clientes BeforeCancel Event begin
// clientes BeforeCancel Event end
//-------------------------------
    header("Location: " . $sActionFileName);
    exit;
  }
//-------------------------------


//-------------------------------
// Build WHERE statement
//-------------------------------
  if($sAction == "update" || $sAction == "delete") 
  {
    $pPK_id_cliente = get_param("PK_id_cliente");
    if( !strlen($pPK_id_cliente)) return;
    $sWhere = "id_cliente=" . tosql($pPK_id_cliente, "Number");
  }
//-------------------------------


//-------------------------------
// Load all form fields into variables
//-------------------------------
  $fldnombre = get_param("nombre");
  $fldrazon_social = get_param("razon_social");
  $flddireccion_calle = get_param("direccion_calle");
  $flddireccion_delegacion = get_param("direccion_delegacion");
  $flddireccion_colonia = get_param("direccion_colonia");
  $flddireccion_estado = get_param("direccion_estado");
  $flddireccion_cp = get_param("direccion_cp");
  $fldtelefono = get_param("telefono");
  $fldestatus = get_param("estatus");
  $fldser_recsel = get_param("ser_recsel");
  $fldser_capaci = get_param("ser_capaci");
  $fldser_conadm = get_param("ser_conadm");
  $fldser_confin = get_param("ser_confin");
  $fldser_concon = get_param("ser_concon");
  $fldser_outadm = get_param("ser_outadm");
  $fldser_admnom = get_param("ser_admnom");
  $fldser_asecal = get_param("ser_asecal");
  $fldemp1 = get_param("emp1");
  $fldemp2 = get_param("emp2");
  $fldemp3 = get_param("emp3");
  $fldemp4 = get_param("emp4");
  $fldemp5 = get_param("emp5");
  $fldemp6 = get_param("emp6");
  $fldemp7 = get_param("emp7");
  $fldemp8 = get_param("emp8");
//-------------------------------
// Validate fields
//-------------------------------
  if($sAction == "insert" || $sAction == "update") 
  {
//-------------------------------
// clientes Check Event begin
// clientes Check Event end
//-------------------------------
    if(!strlen($fldnombre))
       $sClienteErr=$sClienteErr . tohtml("Error: No se puede dejar el campo 'Nombre del Cliente' en blanco.") . "<br>";       
    if(!strlen($fldrazon_social))
       $sClienteErr=$sClienteErr . tohtml("Error: No se puede dejar el campo 'RazonSocial' en blanco.") . "<br>";       
    if(!ereg("[0-9,a-z,A-Z]{1,50}",$fldrazon_social))
       $sClienteErr=$sClienteErr . tohtml("Error: El campo 'Razon Social' contiene caracteres inválidos.") . "<br>";       
    if(!strlen($flddireccion_calle))
       $sClienteErr=$sClienteErr . tohtml("Error: No se puede dejar el campo 'Direccion, Calle y Numero' en blanco.") . "<br>";       
    if(!strlen($flddireccion_estado))
       $sClienteErr=$sClienteErr . tohtml("Error: No se puede dejar el campo 'Direccion, Estado' en blanco.") . "<br>";       
    if(!strlen($flddireccion_cp))
       $sClienteErr=$sClienteErr . tohtml("Error: No se puede dejar el campo 'Direccion, C.P.' en blanco.") . "<br>";       
    if(!ereg("[0-9]{5}",$flddireccion_cp))
       $sClienteErr=$sClienteErr . tohtml("Error: El campo 'Direccion, C.P.' contiene caracteres invalidos.") . "<br>";       

    if(!strlen($fldtelefono))
       $sClienteErr=$sClienteErr . tohtml("Error: No se puede dejar el campo 'Telefono' en blanco.") . "<br>";       
    if(!ereg("[0-9,-,(,)]{1,20}",$fldtelefono))
       $sClienteErr=$sClienteErr . tohtml("Error: El campo 'Telefono' contiene caracteres inválidos.") . "<br>";       
    if($fldestatus==-1)
       $sClienteErr=$sClienteErr . tohtml("Error: Debe seleccionar un estatus para el cliente.") . "<br>";       
    if((strlen($fldemp1)!=0) && ($fldemp1 != -1) && !$fldser_recsel)
       $sClienteErr=$sClienteErr . tohtml("Error: No se puede asignar un servicio a un empleado sin antes activar el servicio.") . "<br>";       
    if((strlen($fldemp1)!=0) && ($fldemp2 != -1) && !$fldser_capaci)
       $sClienteErr=$sClienteErr . tohtml("Error: No se puede asignar un servicio a un empleado sin antes activar el servicio.") . "<br>";       
    if((strlen($fldemp1)!=0) && ($fldemp3 != -1) && !$fldser_conadm)
       $sClienteErr=$sClienteErr . tohtml("Error: No se puede asignar un servicio a un empleado sin antes activar el servicio.") . "<br>";       
    if((strlen($fldemp1)!=0) && ($fldemp4 != -1) && !$fldser_confin)
       $sClienteErr=$sClienteErr . tohtml("Error: No se puede asignar un servicio a un empleado sin antes activar el servicio.") . "<br>";       
    if((strlen($fldemp1)!=0) && ($fldemp5 != -1) && !$fldser_concon)
       $sClienteErr=$sClienteErr . tohtml("Error: No se puede asignar un servicio a un empleado sin antes activar el servicio.") . "<br>";       
    if((strlen($fldemp1)!=0) && ($fldemp6 != -1) && !$fldser_outadm)
       $sClienteErr=$sClienteErr . tohtml("Error: No se puede asignar un servicio a un empleado sin antes activar el servicio.") . "<br>";       
    if((strlen($fldemp1)!=0) && ($fldemp7 != -1) && !$fldser_admnom)
       $sClienteErr=$sClienteErr . tohtml("Error: No se puede asignar un servicio a un empleado sin antes activar el servicio.") . "<br>";       
    if((strlen($fldemp1)!=0) && ($fldemp8 != -1) && !$fldser_asecal)
       $sClienteErr=$sClienteErr . tohtml("Error: No se puede asignar un servicio a un empleado sin antes activar el servicio.") . "<br>";       

    
    if(strlen($sClienteErr)) return;
    
  }
//-------------------------------


//-------------------------------
// Create SQL statement
//-------------------------------
  switch(strtolower($sAction)) 
  {
    case "insert":
//-------------------------------
// clientes Insert Event begin
// clientes Insert Event end
//-------------------------------

        $sSQL = "insert into clientes (" . 
          "nombre,razon_social,direccion_calle," .
          "direccion_colonia,direccion_delegacion," .
          "direccion_estado,direccion_cp,telefono,estatus," .
          "ser_recsel,ser_capaci,ser_conadm,ser_confin," .
          "ser_concon,ser_outadm,ser_admnom,ser_asecal)" . 
          " values (" . 
          tosql($fldnombre, "Text") . ","  .
          tosql($fldrazon_social, "Text") . "," .
          tosql($flddireccion_calle, "Text") . "," .
	  tosql($flddireccion_colonia, "Text") . "," .
          tosql($flddireccion_delegacion, "Text") . "," .
          tosql($flddireccion_estado, "Text") . "," .
          tosql($flddireccion_cp, "Text") . "," .
          tosql($fldtelefono, "Text") . "," .
          tosql($fldestatus, "Number") . "," .
          tosql($fldser_recsel, "Bit") . "," .
          tosql($fldser_capaci, "Bit") . "," .
          tosql($fldser_conadm, "Bit") . "," .
          tosql($fldser_confin, "Bit") . "," .
          tosql($fldser_concon, "Bit") . "," .
          tosql($fldser_outadm, "Bit") . "," .
          tosql($fldser_admnom, "Bit") . "," .
          tosql($fldser_asecal, "Bit") .        
          ")";
    break;
    case "update":

//-------------------------------
// clientes Update Event begin
// clientes Update Event end
//-------------------------------
        $sSQL = "update clientes set " .
          "nombre=" . tosql($fldnombre, "Text") . "," .
          "razon_social=" . tosql($fldrazon_social, "Text") . "," .
          "direccion_calle=" . tosql($flddireccion_calle, "Text") . "," .
          "direccion_colonia=" . tosql($flddireccion_colonia, "Text") . "," .
          "direccion_delegacion=" . tosql($flddireccion_delegacion, "Text") . "," .
          "direccion_estado=" . tosql($flddireccion_estado, "Text") . "," .
          "direccion_cp=" . tosql($flddireccion_cp, "Text") . "," .
          "telefono=" . tosql($fldtelefono, "Text") . "," .
          "estatus=" . tosql($fldestatus,"Number") . "," .
          "ser_recsel=" . tosql($fldser_recsel,"Bit") . "," .
          "ser_capaci=" . tosql($fldser_capaci,"Bit") . "," .
          "ser_conadm=" . tosql($fldser_conadm,"Bit") . "," .
          "ser_confin=" . tosql($fldser_confin,"Bit") . "," .
          "ser_concon=" . tosql($fldser_concon,"Bit") . "," .
          "ser_outadm=" . tosql($fldser_outadm,"Bit") . "," .
          "ser_admnom=" . tosql($fldser_admnom,"Bit") . "," .
          "ser_asecal=" . tosql($fldser_asecal,"Bit");
        $sSQL .= " where " . $sWhere;
        $iSQL = "delete from relclientesempleados where " . $sWhere;
        $db->query($iSQL);
    	
          if((strlen($fldemp1)!=0) && ($fldemp1 != -1))
          {
             $iSQL="INSERT INTO RELCLIENTESEMPLEADOS " .
                   "(id_cliente,id_empleado,tipo_servicio) " .
                   "VALUES(".
                   tosql($pPK_id_cliente, "Number"). "," .
                   tosql($fldemp1, "Number"). ",1)";
             $db->query($iSQL);
          }

          if((strlen($fldemp2)!=0) && ($fldemp2 != -1))
          {
             $iSQL="INSERT INTO RELCLIENTESEMPLEADOS " .
                   "(id_cliente,id_empleado,tipo_servicio) " .
                   "VALUES(".
                   tosql($pPK_id_cliente, "Number"). "," .
                   tosql($fldemp2, "Number"). ",2)";
             $db->query($iSQL);
          }

          if((strlen($fldemp3)!=0) && ($fldemp3 != -1))
          {
             $iSQL="INSERT INTO RELCLIENTESEMPLEADOS " .
                   "(id_cliente,id_empleado,tipo_servicio) " .
                   "VALUES(".
                   tosql($pPK_id_cliente, "Number"). "," .
                   tosql($fldemp3, "Number"). ",3)";
             $db->query($iSQL);
          }

          if((strlen($fldemp4)!=0) && ($fldemp4 != -1))
          {
             $iSQL="INSERT INTO RELCLIENTESEMPLEADOS " .
                   "(id_cliente,id_empleado,tipo_servicio) " .
                   "VALUES(".
                   tosql($pPK_id_cliente, "Number"). "," .
                   tosql($fldemp4, "Number"). ",4)";
             $db->query($iSQL);
          }

          if((strlen($fldemp5)!=0) && ($fldemp5 != -1))
          {
             $iSQL="INSERT INTO RELCLIENTESEMPLEADOS " .
                   "(id_cliente,id_empleado,tipo_servicio) " .
                   "VALUES(".
                   tosql($pPK_id_cliente, "Number"). "," .
                   tosql($fldemp5, "Number"). ",5)";
             $db->query($iSQL);
          }

          if((strlen($fldemp6)!=0) && ($fldemp6 != -1))
          {
             $iSQL="INSERT INTO RELCLIENTESEMPLEADOS " .
                   "(id_cliente,id_empleado,tipo_servicio) " .
                   "VALUES(".
                   tosql($pPK_id_cliente, "Number"). "," .
                   tosql($fldemp6, "Number"). ",6)";
             $db->query($iSQL);
          }

          if((strlen($fldemp7)!=0) && ($fldemp7 != -1))
          {
             $iSQL="INSERT INTO RELCLIENTESEMPLEADOS " .
                   "(id_cliente,id_empleado,tipo_servicio) " .
                   "VALUES(".
                   tosql($pPK_id_cliente, "Number"). "," .
                   tosql($fldemp7, "Number"). ",7)";
             $db->query($iSQL);
          }

          if((strlen($fldemp8)!=0) && ($fldemp8 != -1))
          {
             $iSQL="INSERT INTO RELCLIENTESEMPLEADOS " .
                   "(id_cliente,id_empleado,tipo_servicio) " .
                   "VALUES(".
                   tosql($pPK_id_cliente, "Number"). "," .
                   tosql($fldemp8, "Number"). ",8)";
             $db->query($iSQL);
          }
    break;
    case "delete":
    check_security(3);

//-------------------------------
// clientes Delete Event begin
// clientes Delete Event end
//-------------------------------
        $sSQL = "delete from clientes where " . $sWhere;
        $iSQL = "delete from recordatorios where " . $sWhere;
	    $db->query($iSQL);
        $iSQL = "delete from contactos where " . $sWhere;
	    $db->query($iSQL);
        $iSQL = "delete from relclientesempleados where " . $sWhere;
	    $db->query($iSQL);
    break;
  }
//-------------------------------
//-------------------------------
// clientes BeforeExecute Event begin
// clientes BeforeExecute Event end
//-------------------------------

//-------------------------------
// Execute SQL statement
//-------------------------------
  if(strlen($sClienteErr)) return;
  if($bExecSQL)
    $db->query($sSQL);
  header("Location: " . $sActionFileName);
  exit;

//-------------------------------
// clientes Action end
//-------------------------------
}

//===============================
// Display Record Form
//-------------------------------
function Cliente_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sClienteErr;
  
  $fldid_cliente = "";
  $fldnombre = "";
  $fldrazon_social= "";
  $flddireccion_calle= "";
  $flddireccion_colonia= "";
  $flddireccion_delegacion= "";
  $flddireccion_estado= "";
  $flddireccion_cp= "";
  $fldtelefono= "";

//-------------------------------
// clientes Show begin
//-------------------------------
  $sFormTitle = "Cliente";
  $sWhere = "";
  $bPK = true;
//-------------------------------
// Load primary key and form parameters
//-------------------------------
  if($sClienteErr == "")
  {
    $fldid_cliente = get_param("id_cliente_nav");
    $pid_cliente =  get_param("id_cliente_nav");
    $fldnombre =  get_param("nombre");
    $fldrazon_social=  get_param("razon_social");
    $flddireccion_calle=  get_param("direccion_calle");
    $flddireccion_colonia=  get_param("direccion_colonia");
    $flddireccion_delegacion=  get_param("direccion_delegacion");
    $flddireccion_estado=  get_param("direccion_estado");
    $flddireccion_cp=  get_param("direccion_cp");
    $fldtelefono=  get_param("telefono");
    $fldestatus=  get_param("estatus");
    $fldser_recsel = get_param("ser_recsel");
    $fldser_capaci = get_param("ser_capaci");
    $fldser_conadm = get_param("ser_conadm");
    $fldser_confin = get_param("ser_confin");
    $fldser_concon = get_param("ser_concon");
    $fldser_outadm = get_param("ser_outadm");
    $fldser_admnom = get_param("ser_admnom");
    $fldser_asecal = get_param("ser_asecal");
    $fldemp1 = get_param("emp1");
    $fldemp2 = get_param("emp2");
    $fldemp3 = get_param("emp3");
    $fldemp4 = get_param("emp4");
    $fldemp5 = get_param("emp5");
    $fldemp6 = get_param("emp6");
    $fldemp7 = get_param("emp7");
    $fldemp8 = get_param("emp8");
    $tpl->set_var("ClienteError", "");
  }
  else
  {
    $fldid_cliente = strip(get_param("id_cliente"));
    $pid_cliente =  strip(get_param("PK_id_cliente"));
    $fldnombre =  strip(get_param("nombre"));
    $fldrazon_social=  strip(get_param("razon_social"));
    $flddireccion_calle=  strip(get_param("direccion_calle"));
    $flddireccion_colonia=  strip(get_param("direccion_colonia"));
    $flddireccion_delegacion=  strip(get_param("direccion_delegacion"));
    $flddireccion_estado=  strip(get_param("direccion_estado"));
    $flddireccion_cp=  strip(get_param("direccion_cp"));
    $fldtelefono=  strip(get_param("telefono"));
    $fldestatus= strip(get_param("estatus"));
    $fldser_recsel = strip(get_param("ser_recsel"));
    $fldser_capaci = strip(get_param("ser_capaci"));
    $fldser_conadm = strip(get_param("ser_conadm"));
    $fldser_confin = strip(get_param("ser_confin"));
    $fldser_concon = strip(get_param("ser_concon"));
    $fldser_outadm = strip(get_param("ser_outadm"));
    $fldser_admnom = strip(get_param("ser_admnom"));
    $fldser_asecal = strip(get_param("ser_asecal"));
  $fldemp1 = get_param("emp1");
  $fldemp2 = get_param("emp2");
  $fldemp3 = get_param("emp3");
  $fldemp4 = get_param("emp4");
  $fldemp5 = get_param("emp5");
  $fldemp6 = get_param("emp6");
  $fldemp7 = get_param("emp7");
  $fldemp8 = get_param("emp8");
    $tpl->set_var("sClienteErr", $sClienteErr);
    $tpl->set_var("FormTitle", $sFormTitle);
    $tpl->parse("ClienteError", false);
  }
//-------------------------------

//-------------------------------
// Load all form fields

//-------------------------------

//-------------------------------
// Build WHERE statement
//-------------------------------
  
  if( !strlen($pid_cliente)) $bPK = false;
  
  $sWhere .= "id_cliente=" . tosql($pid_cliente, "Number");
  $tpl->set_var("PK_id_cliente", $pid_cliente);
//-------------------------------
//-------------------------------
// clientes Open Event begin
// clientes Open Event end
//-------------------------------

  $tpl->set_var("FormTitle", $sFormTitle);

//-------------------------------
// Build SQL statement and execute query
//-------------------------------
  $sSQL = "select * from clientes where " . $sWhere;
  // Execute SQL statement
  $db->query($sSQL);
  $bIsUpdateMode = ($bPK && !($sAction == "insert" && $sForm == "cliente") && $db->next_record());
//-------------------------------

//-------------------------------
// Load all fields into variables from recordset or input parameters
//-------------------------------
  if($bIsUpdateMode)
  {
    $fldid_cliente = $db->f("id_cliente");
//-------------------------------
// Load data from recordset when form displayed first time
//-------------------------------
    if($sClienteErr == "") 
    {
      $fldnombre = $db->f("nombre");
      $fldrazon_social = $db->f("razon_social");
      $flddireccion_calle = $db->f("direccion_calle");
      $flddireccion_colonia = $db->f("direccion_colonia");
      $flddireccion_estado = $db->f("direccion_estado");
      $flddireccion_delegacion = $db->f("direccion_delegacion");
      $flddireccion_cp = $db->f("direccion_cp");
      $fldestatus=$db->f("estatus");
      $fldtelefono = $db->f("telefono");
      $fldser_recsel = $db->f("ser_recsel");
      $fldser_capaci = $db->f("ser_capaci");
      $fldser_conadm = $db->f("ser_conadm");
      $fldser_confin = $db->f("ser_confin");
      $fldser_concon = $db->f("ser_concon");
      $fldser_outadm = $db->f("ser_outadm");
      $fldser_admnom = $db->f("ser_admnom");
      $fldser_asecal = $db->f("ser_asecal");
    }

    
      $sSQL = "select * from relclientesempleados where " . $sWhere . " AND tipo_servicio=1";
      $db->query($sSQL);
      if($db->next_record())	      
	      $fldemp1 = $db->f("id_empleado");
      else
              $fldemp1 = null;

      $sSQL = "select * from relclientesempleados where " . $sWhere . " AND tipo_servicio=2";
      $db->query($sSQL);
      if($db->next_record())	      
	      $fldemp2 = $db->f("id_empleado");
      else
              $fldemp2 = null;

      $sSQL = "select * from relclientesempleados where " . $sWhere . " AND tipo_servicio=3";
      $db->query($sSQL);
      if($db->next_record())	      
	      $fldemp3 = $db->f("id_empleado");
      else
              $fldemp3 = null;

      $sSQL = "select * from relclientesempleados where " . $sWhere . " AND tipo_servicio=4";
      $db->query($sSQL);
      if($db->next_record())	      
	      $fldemp4 = $db->f("id_empleado");
      else
              $fldemp4 = null;

      $sSQL = "select * from relclientesempleados where " . $sWhere . " AND tipo_servicio=5";
      $db->query($sSQL);
      if($db->next_record())	      
	      $fldemp5 = $db->f("id_empleado");
      else
              $fldemp5 = null;

      $sSQL = "select * from relclientesempleados where " . $sWhere . " AND tipo_servicio=6";
      $db->query($sSQL);
      if($db->next_record())	      
	      $fldemp6 = $db->f("id_empleado");
      else
              $fldemp6 = null;

      $sSQL = "select * from relclientesempleados where " . $sWhere . " AND tipo_servicio=7";
      $db->query($sSQL);
      if($db->next_record())	      
	      $fldemp7 = $db->f("id_empleado");
      else
              $fldemp7 = null;

      $sSQL = "select * from relclientesempleados where " . $sWhere . " AND tipo_servicio=8";
      $db->query($sSQL);
      if($db->next_record())	      
	      $fldemp8 = $db->f("id_empleado");
      else
              $fldemp8 = null;

    if($fldser_recsel)
	    $tpl->set_var("chk_ser_recsel","CHECKED");
    if($fldser_capaci)   
	    $tpl->set_var("chk_ser_capaci","CHECKED");
    if($fldser_conadm)
	    $tpl->set_var("chk_ser_conadm","CHECKED");
    if($fldser_confin)
	    $tpl->set_var("chk_ser_confin","CHECKED");
    if($fldser_concon)
	    $tpl->set_var("chk_ser_concon","CHECKED");
    if($fldser_outadm)
	    $tpl->set_var("chk_ser_outadm","CHECKED");
    if($fldser_admnom)
	    $tpl->set_var("chk_ser_admnom","CHECKED");
    if($fldser_asecal)
	    $tpl->set_var("chk_ser_asecal","CHECKED");
    $tpl->set_var("emp1",genselect_fromemps("emp1",$fldemp1));
    $tpl->set_var("emp2",genselect_fromemps("emp2",$fldemp2));
    $tpl->set_var("emp3",genselect_fromemps("emp3",$fldemp3));
    $tpl->set_var("emp4",genselect_fromemps("emp4",$fldemp4));
    $tpl->set_var("emp5",genselect_fromemps("emp5",$fldemp5));
    $tpl->set_var("emp6",genselect_fromemps("emp6",$fldemp6));
    $tpl->set_var("emp7",genselect_fromemps("emp7",$fldemp7));
    $tpl->set_var("emp8",genselect_fromemps("emp8",$fldemp8));



    $tpl->set_var("ClienteInsert", "");
    $tpl->parse("Servicios",false);
    $tpl->parse("ClienteEdit", false);
//-------------------------------
// clientes ShowEdit Event begin
// clientes ShowEdit Event end
//-------------------------------
  }
  else
  {
    if($sClienteErr == "")
    {
      $fldid_cliente = tohtml(get_param("id_cliente"));
    }
    $tpl->set_var("ClienteEdit", "");
    $tpl->set_var("Servicios", "");
    $tpl->parse("ClienteInsert", false);
//-------------------------------
// clientes ShowInsert Event begin
// clientes ShowInsert Event end
//-------------------------------
  }
  $tpl->parse("ClienteCancel", false);
//-------------------------------
// clientes Show Event begin
// clientes Show Event end
//-------------------------------

//-------------------------------
// Show form field
//-------------------------------
    $tpl->set_var("id_cliente", tohtml($fldid_cliente));
    $tpl->set_var("nombre", tohtml($fldnombre));
    $tpl->set_var("razon_social", tohtml($fldrazon_social));
    $tpl->set_var("direccion_calle", tohtml($flddireccion_calle));
    $tpl->set_var("direccion_colonia", tohtml($flddireccion_colonia));
    $tpl->set_var("direccion_delegacion", tohtml($flddireccion_delegacion));
    $tpl->set_var("direccion_estado", tohtml($flddireccion_estado));
    $tpl->set_var("direccion_cp", tohtml($flddireccion_cp));
    $tpl->set_var("telefono", tohtml($fldtelefono));
    $fldestatus=genselect_fromcat("Estatus del Cliente","estatus",$fldestatus);
    $tpl->set_var("estatus", $fldestatus);

    if(!security_info(3))
 	$tpl->set_var("ClienteDelete","");
    else 
        $tpl->parse("ClienteDelete", false);   


    $tpl->parse("FormCliente", false);

//-------------------------------
// clientes Close Event begin
// clientes Close Event end
//-------------------------------

//-------------------------------
// clientes Show end
//-------------------------------
}
//===============================
?>
