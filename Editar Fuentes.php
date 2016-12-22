<?php
/*********************************************************************************
 *       Filename: Editar Fuentes.php
 *       Proyecto COCA Consultores
 *       Adrian Bisiacchi
 *       PHP 4.0 & Templates Escrito el 10/04/2002
 *********************************************************************************/

//-------------------------------
// Insertar Empleados CustomIncludes begin

include ("./common.php");

// Insertar Empleados CustomIncludes end
//-------------------------------

session_start();

//===============================
// Save Page and File Name available into variables
//-------------------------------
$filename = "Editar Fuentes.php";
$template_filename = "Editar Fuentes.html";
//===============================



//===============================
// Insertar Fuentes PageSecurity begin
check_security(2);
// Insertar Fuentes PageSecurity end
//===============================

//===============================
// Insertar Fuentes Open Event begin
// Insertar Fuentes Open Event end
//===============================

//===============================
// Insertar Fuentes OpenAnyPage Event start
// Insertar Fuentes OpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

// Insertar Fuente Show begin

//===============================
// Perform the form's action
//-------------------------------
// Initialize error variables
//-------------------------------
$sFuenteErr = "";

//-------------------------------
// Select the FormAction
//-------------------------------


switch ($sForm) {
  case "Fuente":
    Fuentes_action($sAction);
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
//$tpl->load_file($header_filename, "Header");
//-------------------------------
$tpl->set_var("FileName", $filename);



//-------------------------------
// Step through each form
//-------------------------------
Fuentes_show();

//-------------------------------
// Process page templates
//-------------------------------
//$tpl->parse("Header", false);
//-------------------------------
// Output the page to the browser
//-------------------------------
   $tpl->pparse("main", false);
// Insertar Fuentes Show end

//===============================
// Insertar Fuentes Close Event begin
// Insertar Fuentes Close Event end
//===============================
//********************************************************************************


//===============================
// Action of the Record Form
//-------------------------------
function Fuentes_action($sAction)
{
//-------------------------------
// Initialize variables  
//-------------------------------
  global $db;
  global $tpl;
  global $sForm;
  global $sFuenteErr;
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
 if(strlen(get_param("id_contacto")))
 {
   $TransitParams="?id_contacto=" . get_param("id_contacto");
 }

//-------------------------------
// Fuentes Action begin
//-------------------------------
  $sActionFileName = "Inicio Fuentes.php";

//-------------------------------
// CANCEL action
//-------------------------------
  if($sAction == "cancel")
  {

//-------------------------------
// Fuentess BeforeCancel Event begin
// Fuentess BeforeCancel Event end
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
    $pPK_id_Fuente = get_param("PK_id_Fuente");
    if( !strlen($pPK_id_Fuente)) return;
    $sWhere = " id_Fuente=" . tosql($pPK_id_Fuente, "Number");
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
// Fuentess Check Event begin
// Fuentess Check Event end
//-------------------------------
    if(!strlen($fldnombre))
       $sFuenteErr=$sFuenteErr . tohtml("Error: No se puede dejar el campo 'Nombre(s)' en blanco.") . "<br>";       


    if(strlen($sFuenteErr)) return;
  }
//-------------------------------


//-------------------------------
// Create SQL statement
//-------------------------------
  switch(strtolower($sAction)) 
  {
    case "insert":
//-------------------------------
// Fuentess Insert Event begin
// Fuentess Insert Event end
//-------------------------------

        $sSQL = "insert into Fuentes  (nombre) " .
                " values (" . tosql($fldnombre, "Text") . ")";
    break;
    case "update":

//-------------------------------
// Fuentess Update Event begin
// Fuentess Update Event end
//-------------------------------

	  $sSQL = "update Fuentes set " .
          "nombre=" . tosql($fldnombre, "Text");

      
          $sSQL .= " where " . $sWhere;
    break;
    case "delete":
//-------------------------------
// Fuentess Delete Event begin
// Fuentess Delete Event end
//-------------------------------
        $sSQL = "DELETE FROM Fuentes where " . $sWhere;
    break;
  }
//-------------------------------
//-------------------------------
// Fuentes BeforeExecute Event begin
// Fuentes BeforeExecute Event end
//-------------------------------

//-------------------------------
// Execute SQL statement
//-------------------------------
  if(strlen($sFuenteErr)) return;
  if($bExecSQL)
    $db->query($sSQL);
  header("Location: " . $sActionFileName . $TransitParams);
  exit;

//-------------------------------
// Fuentess Action end
//-------------------------------
}

//===============================
// Display Record Form
//-------------------------------
function Fuentes_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sFuenteErr;
  
  $fldid_Fuente = "";
  $fldFuente = "";


//-------------------------------
// Carreass Show begin
//-------------------------------
  $sFormTitle = "Fuentes";
  $sWhere = "";
  $bPK = true;
//-------------------------------
// Load primary key and form parameters
//-------------------------------
  if($sFuenteErr == "")
  {
    $pid_Fuente=get_param("id_Fuente");
    $fldnombre = get_param("nombre");

   
    $tpl->set_var("FuenteError", "");
  }
  else
  {
    $pid_Fuente=strip(get_param("id_Fuente"));
    $fldnombre = strip(get_param("nombre"));


    $tpl->set_var("sFuenteErr", $sFuenteErr);
    $tpl->set_var("FormTitle", $sFormTitle);
    $tpl->parse("FuenteError", false);
  }
//-------------------------------

//-------------------------------
// Load all form fields

//-------------------------------

//-------------------------------
// Build WHERE statement
//-------------------------------
  
  if( !strlen($pid_Fuente)) $bPK = false;
  
  $sWhere .= "id_Fuente=" . tosql($pid_Fuente, "Number");
  $tpl->set_var("PK_id_Fuente", $pid_Fuente);
//-------------------------------
//-------------------------------
// Fuentess Open Event begin
// Fuentess Open Event end
//-------------------------------

  $tpl->set_var("FormTitle", $sFormTitle);

//-------------------------------
// Build SQL statement and execute query
//-------------------------------
  $sSQL = "select * from Fuentes where " . $sWhere;
  // Execute SQL statement
  $db->query($sSQL);
  $bIsUpdateMode = ($bPK && !($sAction == "insert" && $sForm == "Fuente") && $db->next_record());
//-------------------------------

//-------------------------------
// Load all fields into variables from recordset or input parameters
//-------------------------------
  if($bIsUpdateMode)
  {
    $fldid_Fuentes = $db->f("id_Fuentes");
//-------------------------------
// Load data from recordset when form displayed first time
//-------------------------------
    if($sFuenteErr == "") 
    {
      $fldnombre = $db->f("nombre");

    }
    $tpl->set_var("FuenteInsert", "");
    $tpl->parse("FuenteEdit", false);
//-------------------------------
// Fuentess ShowEdit Event begin
// Fuentess ShowEdit Event end
//-------------------------------
  }
  else
  {
    if($sFuenteErr == "")
    {
      $fldid_Fuente = tohtml(get_param("id_Fuente"));
    }
    $tpl->set_var("FuenteEdit", "");
    $tpl->parse("FuenteInsert", false);
//-------------------------------
// Fuentess ShowInsert Event begin
// Fuentess ShowInsert Event end
//-------------------------------
  }
  $tpl->parse("FuenteCancel", false);
//-------------------------------
// Fuentess Show Event begin
// Fuentess Show Event end
//-------------------------------

//-------------------------------
// Show form field
//-------------------------------
    $tpl->set_var("id_Fuentes", tohtml($fldid_Fuentes));
    $tpl->set_var("nombre", tohtml($fldnombre));
 

//    $tpl->set_var("apaterno", tohtml($fldapaterno));
//    $tpl->set_var("amaterno", tohtml($fldamaterno));
//    $tpl->set_var("puesto", tohtml($fldpuesto));
//    $tpl->set_var("direccion_calle", tohtml($flddireccion_calle));
//    $tpl->set_var("direccion_colonia", tohtml($flddireccion_colonia));
//    $tpl->set_var("direccion_delegacion", tohtml($flddireccion_delegacion));
//    $tpl->set_var("direccion_estado", tohtml($flddireccion_estado));
//    $tpl->set_var("direccion_cp", tohtml($flddireccion_cp));
//    $tpl->set_var("telefono_casa", tohtml($fldtelefono_casa));
//    $tpl->set_var("telefono_celular", tohtml($fldtelefono_celular));
//    $tpl->set_var("telefono_trabajo", tohtml($fldtelefono_trabajo));
//    $tpl->set_var("telefono_fax", tohtml($fldtelefono_fax));
//    $tpl->set_var("id_cliente", tohtml($fldid_cliente));
//    $tpl->set_var("email", tohtml($fldemail));
//    $tpl->set_var("login", tohtml($fldlogin));
//    $tpl->set_var("password", tohtml($fldpassword));
//    $tpl->set_var("password2", tohtml($fldpassword));
//    $fldaccess_level=genselect_fromcat("Nivel de Acceso","access_level",$fldaccess_level);
//    $tpl->set_var("access_level", $fldaccess_level);

    $tpl->parse("FormFuente", false);

//-------------------------------
// Fuentess Close Event begin
// Fuentess Close Event end
//-------------------------------

//-------------------------------
// Fuentess Show end
//-------------------------------
}
//===============================
?>
