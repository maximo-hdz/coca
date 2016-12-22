<?php
/*********************************************************************************
 *       Filename: Insertar D
 *       Proyecto Dennis Crowley
 *       Victor Medrano - NPS México - Colabora Adrian Bisiacchi
 *       PHP 4.0 & Templates Escrito el 04/03/2002
 *********************************************************************************/

//-------------------------------
// Insertar Departamento CustomIncludes begin

include ("./common.php");
include ("./Header.php");

// Insertar Departamento CustomIncludes end
//-------------------------------

session_start();

//===============================
// Save Page and File Name available into variables
//-------------------------------
$filename = "Insertar Departamento.php";
$template_filename = "Insertar Departamento.html";
//===============================



//===============================
// Insertar Departamento PageSecurity begin
check_security(3);
// Insertar Departamento PageSecurity end
//===============================

//===============================
// Insertar Departamento Open Event begin
// Insertar Departamento Open Event end
//===============================

//===============================
// Insertar Departamento OpenAnyPage Event start
// Insertar Departamento OpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

// Insertar Departamento Show begin

//===============================
// Perform the form's action
//-------------------------------
// Initialize error variables
//-------------------------------
$sDepartamentosErr = "";

//-------------------------------
// Select the FormAction
//-------------------------------
switch ($sForm) {
  case "Departamentos":
    Departamentos_action($sAction);
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
Header_show();Departamentos_show();

//-------------------------------
// Process page templates
//-------------------------------
$tpl->parse("Header", false);
//-------------------------------
// Output the page to the browser
//-------------------------------
$tpl->pparse("main", false);
// Insertar Departamento Show end

//===============================
// Insertar Departamento Close Event begin
// Insertar Departamento Close Event end
//===============================
//********************************************************************************


//===============================
// Action of the Record Form
//-------------------------------
function Departamentos_action($sAction)
{
//-------------------------------
// Initialize variables  
//-------------------------------
  global $db;
  global $tpl;
  global $sForm;
  global $sDepartamentosErr;
  $bExecSQL = true;
  $sActionFileName = "";
  $sWhere = "";
  $bErr = false;
  $pPKdep_id = "";
  $fldnombre = "";
//-------------------------------

//-------------------------------
// Departamentos Action begin
//-------------------------------
  $sActionFileName = "Tabla Departamentos.php";

//-------------------------------
// CANCEL action
//-------------------------------
  if($sAction == "cancel")
  {

//-------------------------------
// Departamentos BeforeCancel Event begin
// Departamentos BeforeCancel Event end
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
    $pPKdep_id = get_param("PK_dep_id");
    if( !strlen($pPKdep_id)) return;
    $sWhere = "dep_id=" . tosql($pPKdep_id, "Number");
  }
//-------------------------------


//-------------------------------
// Load all form fields into variables
//-------------------------------
  $fldnombre = get_param("nombre");

//-------------------------------
// Validate fields
//-------------------------------
  if($sAction == "insert" || $sAction == "update") 
  {
//-------------------------------
// Departamentos Check Event begin
// Departamentos Check Event end
//-------------------------------
    if(strlen($sDepartamentosErr)) return;
  }
//-------------------------------


//-------------------------------
// Create SQL statement
//-------------------------------
  switch(strtolower($sAction)) 
  {
    case "insert":
//-------------------------------
// Departamentos Insert Event begin
// Departamentos Insert Event end
//-------------------------------
        $sSQL = "insert into deps (" . 
          "name)" . 
          " values (" . 
          tosql($fldnombre, "Text") . 
          ")";
    break;
    case "update":

//-------------------------------
// Departamentos Update Event begin
// Departamentos Update Event end
//-------------------------------
        $sSQL = "update deps set " .
          "name=" . tosql($fldnombre, "Text");
        $sSQL .= " where " . $sWhere;
    break;
    case "delete":
//-------------------------------
// Departamentos Delete Event begin
// Departamentos Delete Event end
//-------------------------------
        $sSQL = "delete from deps where " . $sWhere;
    break;
  }
//-------------------------------
//-------------------------------
// Departamentos BeforeExecute Event begin
// Departamentos BeforeExecute Event end
//-------------------------------

//-------------------------------
// Execute SQL statement
//-------------------------------
  if(strlen($sDepartamentosErr)) return;
  if($bExecSQL)
    $db->query($sSQL);
  header("Location: " . $sActionFileName);
  exit;

//-------------------------------
// Departamentos Action end
//-------------------------------
}

//===============================
// Display Record Form
//-------------------------------
function Departamentos_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sDepartamentosErr;
  
  $flddep_id = "";
  $fldnombre = "";
//-------------------------------
// Departamentos Show begin
//-------------------------------
  $sFormTitle = "Departamentos";
  $sWhere = "";
  $bPK = true;
//-------------------------------
// Load primary key and form parameters
//-------------------------------
  if($sDepartamentosErr == "")
  {
    $flddep_id = get_param("dep_id");
    $pdep_id = get_param("dep_id");
    $tpl->set_var("DepartamentosError", "");
  }
  else
  {
    $flddep_id = strip(get_param("dep_id"));
    $fldnombre = strip(get_param("nombre"));
    $pdep_id = get_param("PK_dep_id");
    $tpl->set_var("sDepartamentosErr", $sDepartamentosErr);
    $tpl->set_var("FormTitle", $sFormTitle);
    $tpl->parse("DepartamentosError", false);
  }
//-------------------------------

//-------------------------------
// Load all form fields

//-------------------------------

//-------------------------------
// Build WHERE statement
//-------------------------------
  
  if( !strlen($pdep_id)) $bPK = false;
  
  $sWhere .= "dep_id=" . tosql($pdep_id, "Number");
  $tpl->set_var("PK_dep_id", $pdep_id);
//-------------------------------
//-------------------------------
// Departamentos Open Event begin
// Departamentos Open Event end
//-------------------------------

  $tpl->set_var("FormTitle", $sFormTitle);

//-------------------------------
// Build SQL statement and execute query
//-------------------------------
  $sSQL = "select * from deps where " . $sWhere;
  // Execute SQL statement
  $db->query($sSQL);
  $bIsUpdateMode = ($bPK && !($sAction == "insert" && $sForm == "Departamentos") && $db->next_record());
//-------------------------------

//-------------------------------
// Load all fields into variables from recordset or input parameters
//-------------------------------
  if($bIsUpdateMode)
  {
    $flddep_id = $db->f("dep_id");
//-------------------------------
// Load data from recordset when form displayed first time
//-------------------------------
    if($sDepartamentosErr == "") 
    {
      $fldnombre = $db->f("name");
    }
    $tpl->set_var("DepartamentosInsert", "");
    $tpl->parse("DepartamentosEdit", false);
//-------------------------------
// Departamentos ShowEdit Event begin
// Departamentos ShowEdit Event end
//-------------------------------
  }
  else
  {
    if($sDepartamentosErr == "")
    {
      $flddep_id = tohtml(get_param("dep_id"));
    }
    $tpl->set_var("DepartamentosEdit", "");
    $tpl->parse("DepartamentosInsert", false);
//-------------------------------
// Departamentos ShowInsert Event begin
// Departamentos ShowInsert Event end
//-------------------------------
  }
  $tpl->parse("DepartamentosCancel", false);
//-------------------------------
// Departamentos Show Event begin
// Departamentos Show Event end
//-------------------------------

//-------------------------------
// Show form field
//-------------------------------
    $tpl->set_var("dep_id", tohtml($flddep_id));
    $tpl->set_var("nombre", tohtml($fldnombre));
  $tpl->parse("FormDepartamentos", false);

//-------------------------------
// Departamentos Close Event begin
// Departamentos Close Event end
//-------------------------------

//-------------------------------
// Departamentos Show end
//-------------------------------
}
//===============================
?>