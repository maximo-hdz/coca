<?php
/*********************************************************************************
 *       Filename: Editar Recordatorio.php
 *       Proyecto COCA Consultores
 *       Adrian Bisiacchi
 *       PHP 4.0 & Templates Escrito el 10/04/2002
 *********************************************************************************/

//-------------------------------
// Insertar Recordatorio CustomIncludes begin

include ("./common.php");

// Insertar Recordatorio CustomIncludes end
//-------------------------------

session_start();

//===============================
// Save Page and File Name available into variables
//-------------------------------
$filename = "Editar Recordatorio.php";
$template_filename = "Editar Recordatorio.html";
//===============================



//===============================
// Insertar Recordatorio PageSecurity begin
//check_security(3);
// Insertar Recordatorio PageSecurity end
//===============================

//===============================
// Insertar Recordatorio Open Event begin
// Insertar Recordatorio Open Event end
//===============================

//===============================
// Insertar Recordatorio OpenAnyPage Event start
// Insertar Recordatorio OpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

// Insertar Recordatorio Show begin

//===============================
// Perform the form's action
//-------------------------------
// Initialize error variables
//-------------------------------
$sRecordatorioErr = "";

//-------------------------------
// Select the FormAction
//-------------------------------

switch($sForm)
{
  case "Recordatorio":
    Recordatorio_action($sAction);
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
Recordatorio_show();

//-------------------------------
// Process page templates
//-------------------------------
//$tpl->parse("Header", false);
//-------------------------------
// Output the page to the browser
//-------------------------------
   $tpl->pparse("main", false);
// Insertar Recordatorio Show end

//===============================
// Insertar Recordatorio Close Event begin
// Insertar Recordatorio Close Event end
//===============================
//********************************************************************************


//===============================
// Action of the Record Form
//-------------------------------
function Recordatorio_action($sAction)
{
//-------------------------------
// Initialize variables  
//-------------------------------
  global $db;
  global $tpl;
  global $sForm;
  global $sRecordatorioErr;
  $bExecSQL = true;
  $sActionFileName = "";
  $sWhere = "";
  $bErr = false;
  $pPKcon_id = "";
  $fldnombre = "";
  $TransitParams="";

//-------------------------------
// Init TransitParams
//-------------------------------
 if(strlen(get_param("id_cliente_nav")))
 {
   $TransitParams="?id_cliente_nav=" . get_param("id_cliente_nav");
 }
 else
 {
   $SQL="select id_cliente from Recordatorios where id_Recordatorio=". get_param("PK_id_Recordatorio");
   $db->query($SQL);
   $db->next_record();
   $TransitParams="?id_cliente_nav=" . $db->f("id_cliente"); 
 }

//-------------------------------
// Recordatorio Action begin
//-------------------------------
  $sActionFileName = "Listar Recordatorios.php";

//-------------------------------
// CANCEL action
//-------------------------------
  if($sAction == "cancel")
  {

//-------------------------------
// Recordatorios BeforeCancel Event begin
// Recordatorios BeforeCancel Event end
//-------------------------------
    header("Location: " . $sActionFileName . $TransitParams);
    exit;
  }
//-------------------------------


//-------------------------------
// Build WHERE statement
//-------------------------------
  if($sAction == "update" || $sAction == "delete") 
  {
    $pPK_id_Recordatorio = get_param("PK_id_Recordatorio");
    if( !strlen($pPK_id_Recordatorio)) return;
    $sWhere = "id_Recordatorio=" . tosql($pPK_id_Recordatorio, "Number");
  }
//-------------------------------


//-------------------------------
// Load all form fields into variables
//-------------------------------
  $fldfecha_aplicacion = get_param("fecha_aplicacion");
  $fldnotas = get_param("notas");
  $fldid_cliente = get_param("id_cliente_nav");
  $fldestatus = get_param("estatus");
//-------------------------------
// Validate fields
//-------------------------------
  if($sAction == "insert" || $sAction == "update") 
  {
//-------------------------------
// Recordatorios Check Event begin
// Recordatorios Check Event end
//-------------------------------
    if(!strlen($fldnotas))
       $sRecordatorioErr=$sRecordatorioErr . tohtml("Error: No se puede dejar el campo 'Notas' en blanco.") . "<br>";       

    if(strlen($fldfecha_aplicacion) && !is_date($fldfecha_aplicacion))
       $sRecordatorioErr=$sRecordatorioErr . tohtml("Error: La fecha del recordatorio es invalida.") . "<br>";       

    if(strlen($sRecordatorioErr)) return;
  }
//----------------------------

//    echo($fldestatus);
    if(strlen($fldestatus)==0)
	$fldestatus="0";

    if(strlen($fldfecha_aplicacion)==0)
       $fldfecha_aplicacion="null";
    else
       $fldfecha_aplicacion = tosql($fldfecha_aplicacion, "Date");
//-------------------------------
// Create SQL statement
//-------------------------------
  switch(strtolower($sAction)) 
  {
    case "insert":
//-------------------------------
// Recordatorios Insert Event begin
// Recordatorios Insert Event end
//-------------------------------

        $sSQL = "insert into Recordatorios (" . 
          "id_cliente,notas,fecha_aplicacion,estatus)" .
          " values (" . 
          tosql($fldid_cliente, "Number") . "," .
          tosql($fldnotas, "Text") . ","  .
	  $fldfecha_aplicacion . "," .
          tosql($fldestatus, "Number") .
          ")";
    break;

    case "update":

//-------------------------------
// Recordatorios Update Event begin
// Recordatorios Update Event end
//-------------------------------
        $sSQL = "update Recordatorios set " .
          "notas=" . tosql($fldnotas, "Text") . "," .
          "fecha_aplicacion=" . $fldfecha_aplicacion . "," .
          "estatus=" . tosql($fldestatus, "Number") .
        $sSQL .= " where " . $sWhere;
    break;
  }
//-------------------------------
//-------------------------------
// Recordatorios BeforeExecute Event begin
// Recordatorios BeforeExecute Event end
//-------------------------------

//-------------------------------
// Execute SQL statement
//-------------------------------
  if(strlen($sRecordatorioErr)) return;
  if($bExecSQL)
    $db->query($sSQL);
  header("Location: " . $sActionFileName . $TransitParams);
  exit;

//-------------------------------
// Recordatorios Action end
//-------------------------------
}

//===============================
// Display Record Form
//-------------------------------
function Recordatorio_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sRecordatorioErr;
  
  $fldid_Recordatorio = "";
  $fldnotas = "";
  $fldfecha_aplicacion= "";
  $fldestatus= "";
  $fldid_cliente_nav="";

//-------------------------------
// Recordatorios Show begin
//-------------------------------
  $sFormTitle = "Recordatorio";
  $sWhere = "";
  $bPK = true;
//-------------------------------
// Load primary key and form parameters
//-------------------------------
  if($sRecordatorioErr == "")
  {
    $fldid_Recordatorio = get_param("id_Recordatorio");
    $pid_Recordatorio =  get_param("id_Recordatorio");
    $fldnotas = get_param("notas");
    $fldfecha_aplicacion = get_param("fecha_aplicacion");
    $fldestatus = get_param("estatus");
    $fldid_cliente_nav = get_param("id_cliente_nav");
    $tpl->set_var("RecordatorioError", "");
  }
  else
  {
    $fldid_Recordatorio = strip(get_param("id_Recordatorio"));
    $pid_Recordatorio =  strip(get_param("id_Recordatorio"));
    $fldnotas = strip(get_param("notas"));
    $fldfecha_aplicacion = strip(get_param("fecha_aplicacion"));
    $fldestatus = strip(get_param("estatus"));
    $fldid_cliente_nav = strip(get_param("id_cliente_nav"));
    $tpl->set_var("sRecordatorioErr", $sRecordatorioErr);
    $tpl->set_var("FormTitle", $sFormTitle);
    $tpl->parse("RecordatorioError", false);
  }
//-------------------------------

//-------------------------------
// Load all form fields

//-------------------------------

//-------------------------------
// Build WHERE statement
//-------------------------------
  
  if( !strlen($pid_Recordatorio)) $bPK = false;
  
  $sWhere .= "id_Recordatorio=" . tosql($pid_Recordatorio, "Number");
  $tpl->set_var("PK_id_Recordatorio", $pid_Recordatorio);
//-------------------------------
//-------------------------------
// Recordatorios Open Event begin
// Recordatorios Open Event end
//-------------------------------

  $tpl->set_var("FormTitle", $sFormTitle);

//-------------------------------
// Build SQL statement and execute query
//-------------------------------
  $sSQL = "select * from Recordatorios where " . $sWhere;
  // Execute SQL statement
  $db->query($sSQL);
  $bIsUpdateMode = ($bPK && !($sAction == "insert" && $sForm == "Recordatorio") && $db->next_record());
//-------------------------------

//-------------------------------
// Load all fields into variables from recordset or input parameters
//-------------------------------
  if($bIsUpdateMode)
  {
    $fldid_Recordatorio = $db->f("id_Recordatorio");
//-------------------------------
// Load data from recordset when form displayed first time
//-------------------------------
    if($sRecordatorioErr == "") 
    {
      $fldnotas = $db->f("notas");
      $fldfecha_aplicacion = $db->f("fecha_aplicacion");
      $fldestatus = $db->f("estatus");
      $fldid_cliente_nav = $db->f("id_cliente");
    }
    $tpl->set_var("RecordatorioInsert", "");
    $tpl->parse("RecordatorioEdit", false);
//-------------------------------
// Recordatorios ShowEdit Event begin
// Recordatorios ShowEdit Event end
//-------------------------------
  }
  else
  {
    if($sRecordatorioErr == "")
    {
      $fldid_Recordatorio = tohtml(get_param("id_Recordatorio"));
    }
    $tpl->set_var("RecordatorioEdit", "");
    $tpl->parse("RecordatorioInsert", false);
//-------------------------------
// Recordatorios ShowInsert Event begin
// Recordatorios ShowInsert Event end
//-------------------------------
  }
  $tpl->parse("RecordatorioCancel", false);
//-------------------------------
// Recordatorios Show Event begin
// Recordatorios Show Event end
//-------------------------------

//-------------------------------
// Show form field
//-------------------------------
    $tpl->set_var("id_Recordatorio", tohtml($fldid_Recordatorio));
    $tpl->set_var("notas", tohtml($fldnotas));
    $tpl->set_var("fecha_aplicacion", tohtml($fldfecha_aplicacion));
    $tpl->set_var("estatus", tohtml($fldestatus));
    $tpl->set_var("id_cliente_nav", tohtml($fldid_cliente_nav));

    if($fldestatus==1)
       $tpl->set_var("txtestatus","CHECKED");
    else
       $tpl->set_var("txtestatus","");

    $tpl->parse("FormRecordatorio", false);



//-------------------------------
// Recordatorios Close Event begin
// Recordatorios Close Event end
//-------------------------------

//-------------------------------
// Recordatorios Show end
//-------------------------------
}
//===============================
?>
