<?php
/*********************************************************************************
 *       Filename: Editar Contacto.php
 *       Proyecto COCA Consultores
 *       Adrian Bisiacchi
 *       PHP 4.0 & Templates Escrito el 10/04/2002
 *********************************************************************************/

//-------------------------------
// Insertar contacto CustomIncludes begin

include ("./common.php");

// Insertar contacto CustomIncludes end
//-------------------------------

session_start();

//===============================
// Save Page and File Name available into variables
//-------------------------------
$filename = "Editar Contacto.php";
$template_filename = "Editar Contacto.html";
//===============================



//===============================
// Insertar contacto PageSecurity begin
check_security(2);
// Insertar contacto PageSecurity end
//===============================

//===============================
// Insertar contacto Open Event begin
// Insertar contacto Open Event end
//===============================

//===============================
// Insertar contacto OpenAnyPage Event start
// Insertar contacto OpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

// Insertar contacto Show begin

//===============================
// Perform the form's action
//-------------------------------
// Initialize error variables
//-------------------------------
$sContactoErr = "";

//-------------------------------
// Select the FormAction
//-------------------------------


switch ($sForm) {
  case "Contacto":
    contacto_action($sAction);
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
Contacto_show();

//-------------------------------
// Process page templates
//-------------------------------
//$tpl->parse("Header", false);
//-------------------------------
// Output the page to the browser
//-------------------------------
   $tpl->pparse("main", false);
// Insertar contacto Show end

//===============================
// Insertar contacto Close Event begin
// Insertar contacto Close Event end
//===============================
//********************************************************************************


//===============================
// Action of the Record Form
//-------------------------------
function Contacto_action($sAction)
{
//-------------------------------
// Initialize variables  
//-------------------------------
  global $db;
  global $tpl;
  global $sForm;
  global $sContactoErr;
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
 if(strlen(get_param("id_cliente")))
 {
   $TransitParams="?id_cliente_sel=" . get_param("id_cliente");
 }
 else
 {
   $SQL="select id_cliente from contactos where id_contacto=". get_param("PK_id_Contacto");
   $db->query($SQL);
   $db->next_record();
   $TransitParams="?id_cliente_sel=" . $db->f("id_cliente"); 
 }

//-------------------------------
// Contacto Action begin
//-------------------------------
  $sActionFileName = "Inicio Clientes.php";

//-------------------------------
// CANCEL action
//-------------------------------
  if($sAction == "cancel")
  {

//-------------------------------
// contactos BeforeCancel Event begin
// contactos BeforeCancel Event end
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
    $pPK_id_contacto = get_param("PK_id_Contacto");
    if( !strlen($pPK_id_contacto)) return;
    $sWhere = "id_contacto=" . tosql($pPK_id_contacto, "Number");
  }
//-------------------------------


//-------------------------------
// Load all form fields into variables
//-------------------------------
  $fldnombre = get_param("nombre");
  $fldpuesto = get_param("puesto");
  $fldid_cliente = get_param("id_cliente");
  $fldtelefono_casa = get_param("telefono_casa");
  $fldtelefono_celular = get_param("telefono_celular");
  $fldtelefono_trabajo = get_param("telefono_trabajo");
  $fldtelefono_fax = get_param("telefono_fax");
//-------------------------------
// Validate fields
//-------------------------------
  if($sAction == "insert" || $sAction == "update") 
  {
//-------------------------------
// contactos Check Event begin
// contactos Check Event end
//-------------------------------
    if(!strlen($fldnombre))
       $sContactoErr=$sContactoErr . tohtml("Error: No se puede dejar el campo 'Nombre del Contacto' en blanco.") . "<br>";       

    if(!strlen($fldpuesto))
       $sContactoErr=$sContactoErr . tohtml("Error: No se puede dejar el campo 'Puesto' en blanco.") . "<br>";       

    if(!ereg("[0-9,-,(,)]{1,20}",$fldtelefono_casa) && strlen($fldtelefono_casa))
       $sContactoErr=$sContactoErr . tohtml("Error: El campo 'Telefono Casa' contiene caracteres inválidos.") . "<br>";       

    if(!ereg("[0-9,-,(,)]{1,20}",$fldtelefono_celular) && strlen($fldtelefono_celular))
       $sContactoErr=$sContactoErr . tohtml("Error: El campo 'Telefono Celular' contiene caracteres inválidos.") . "<br>";       

    if(!ereg("[0-9,-,(,)]{1,20}",$fldtelefono_trabajo) && strlen($fldtelefono_trabajo))
       $sContactoErr=$sContactoErr . tohtml("Error: El campo 'Telefono Trabajo' contiene caracteres inválidos.") . "<br>";       

    if(!ereg("[0-9,-,(,)]{1,20}",$fldtelefono_fax) && strlen($fldtelefono_fax))
       $sContactoErr=$sContactoErr . tohtml("Error: El campo 'Fax' contiene caracteres inválidos.") . "<br>";       

    if(strlen($sContactoErr)) return;
  }
//-------------------------------


//-------------------------------
// Create SQL statement
//-------------------------------
  switch(strtolower($sAction)) 
  {
    case "insert":
//-------------------------------
// contactos Insert Event begin
// contactos Insert Event end
//-------------------------------

        $sSQL = "insert into contactos (" . 
          "id_cliente,nombre,puesto,telefono_casa," .
          "telefono_celular,telefono_trabajo," .
          "telefono_fax)" . 
          " values (" . 
          tosql($fldid_cliente, "Number") . "," .
          tosql($fldnombre, "Text") . ","  .
          tosql($fldpuesto, "Text") . "," .
          tosql($fldtelefono_casa, "Text") . "," .
	  tosql($fldtelefono_celular, "Text") . "," .
          tosql($fldtelefono_trabajo, "Text") . "," .
          tosql($fldtelefono_fax, "Text") . 
          ")";
    break;
    case "update":

//-------------------------------
// contactos Update Event begin
// contactos Update Event end
//-------------------------------
        $sSQL = "update contactos set " .
          "nombre=" . tosql($fldnombre, "Text") . "," .
          "puesto=" . tosql($fldpuesto, "Text") . "," .
          "telefono_casa=" . tosql($fldtelefono_casa, "Text") . "," .
          "telefono_celular=" . tosql($fldtelefono_celular, "Text") . "," .
          "telefono_trabajo=" . tosql($fldtelefono_trabajo, "Text") . "," .
          "telefono_fax=" . tosql($fldtelefono_fax, "Text") . 
        $sSQL .= " where " . $sWhere;
    break;
    case "delete":
//-------------------------------
// contactos Delete Event begin
// contactos Delete Event end
//-------------------------------
        $sSQL = "UPDATE contactos SET estatus=0 where " . $sWhere;
    break;
  }
//-------------------------------
//-------------------------------
// contactos BeforeExecute Event begin
// contactos BeforeExecute Event end
//-------------------------------

//-------------------------------
// Execute SQL statement
//-------------------------------
  if(strlen($sContactoErr)) return;
  if($bExecSQL)
    $db->query($sSQL);
  header("Location: " . $sActionFileName . $TransitParams);
  exit;

//-------------------------------
// contactos Action end
//-------------------------------
}

//===============================
// Display Record Form
//-------------------------------
function Contacto_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sContactoErr;
  
  $fldid_contacto = "";
  $fldnombre = "";
  $fldpuesto= "";
  $fldtelefono_casa= "";
  $fldtelefono_fax= "";
  $fldtelefono_celular= "";
  $fldtelefono_trabajo= "";
  $fldid_cliente="";

//-------------------------------
// contactos Show begin
//-------------------------------
  $sFormTitle = "Contacto";
  $sWhere = "";
  $bPK = true;
//-------------------------------
// Load primary key and form parameters
//-------------------------------
  if($sContactoErr == "")
  {
    $fldid_contacto = get_param("id_contacto");
    $pid_contacto =  get_param("id_contacto");
    $fldnombre = get_param("nombre");
    $fldpuesto = get_param("puesto");
    $fldtelefono_casa = get_param("telefono_casa");
    $fldtelefono_celular = get_param("telefono_celular");
    $fldtelefono_trabajo = get_param("telefono_trabajo");
    $fldtelefono_fax = get_param("telefono_fax");
    $fldid_cliente=get_param("id_cliente");
    $tpl->set_var("ContactoError", "");
  }
  else
  {
    $fldid_contacto = strip(get_param("id_contacto"));
    $pid_contacto =  strip(get_param("id_contacto"));
    $fldnombre = strip(get_param("nombre"));
    $fldpuesto = strip(get_param("puesto"));
    $fldtelefono_casa = strip(get_param("telefono_casa"));
    $fldtelefono_celular = strip(get_param("telefono_celular"));
    $fldtelefono_trabajo = strip(get_param("telefono_trabajo"));
    $fldtelefono_fax = strip(get_param("telefono_fax"));
    $fldid_cliente=strip(get_param("id_cliente"));
    $tpl->set_var("sContactoErr", $sContactoErr);
    $tpl->set_var("FormTitle", $sFormTitle);
    $tpl->parse("ContactoError", false);
  }
//-------------------------------

//-------------------------------
// Load all form fields

//-------------------------------

//-------------------------------
// Build WHERE statement
//-------------------------------
  
  if( !strlen($pid_contacto)) $bPK = false;
  
  $sWhere .= "id_contacto=" . tosql($pid_contacto, "Number");
  $tpl->set_var("PK_id_Contacto", $pid_contacto);
//-------------------------------
//-------------------------------
// contactos Open Event begin
// contactos Open Event end
//-------------------------------

  $tpl->set_var("FormTitle", $sFormTitle);

//-------------------------------
// Build SQL statement and execute query
//-------------------------------
  $sSQL = "select * from contactos where " . $sWhere;
  // Execute SQL statement
  $db->query($sSQL);
  $bIsUpdateMode = ($bPK && !($sAction == "insert" && $sForm == "contacto") && $db->next_record());
//-------------------------------

//-------------------------------
// Load all fields into variables from recordset or input parameters
//-------------------------------
  if($bIsUpdateMode)
  {
    $fldid_contacto = $db->f("id_contacto");
//-------------------------------
// Load data from recordset when form displayed first time
//-------------------------------
    if($sContactoErr == "") 
    {
      $fldnombre = $db->f("nombre");
      $fldpuesto = $db->f("puesto");
      $fldtelefono_casa = $db->f("telefono_casa");
      $fldtelefono_celular = $db->f("telefono_celular");
      $fldtelefono_trabajo = $db->f("telefono_trabajo");
      $fldtelefono_fax = $db->f("telefono_fax");
    }
    $tpl->set_var("ContactoInsert", "");
    $tpl->parse("ContactoEdit", false);
//-------------------------------
// contactos ShowEdit Event begin
// contactos ShowEdit Event end
//-------------------------------
  }
  else
  {
    if($sContactoErr == "")
    {
      $fldid_contacto = tohtml(get_param("id_contacto"));
    }
    $tpl->set_var("ContactoEdit", "");
    $tpl->parse("ContactoInsert", false);
//-------------------------------
// contactos ShowInsert Event begin
// contactos ShowInsert Event end
//-------------------------------
  }
  $tpl->parse("ContactoCancel", false);
//-------------------------------
// contactos Show Event begin
// contactos Show Event end
//-------------------------------

//-------------------------------
// Show form field
//-------------------------------
    $tpl->set_var("id_contacto", tohtml($fldid_contacto));
    $tpl->set_var("nombre", tohtml($fldnombre));
    $tpl->set_var("puesto", tohtml($fldpuesto));
    $tpl->set_var("telefono_casa", tohtml($fldtelefono_casa));
    $tpl->set_var("telefono_celular", tohtml($fldtelefono_celular));
    $tpl->set_var("telefono_trabajo", tohtml($fldtelefono_trabajo));
    $tpl->set_var("telefono_fax", tohtml($fldtelefono_fax));
    $tpl->set_var("id_cliente", tohtml($fldid_cliente));
    $tpl->parse("FormContacto", false);

//-------------------------------
// contactos Close Event begin
// contactos Close Event end
//-------------------------------

//-------------------------------
// contactos Show end
//-------------------------------
}
//===============================
?>
