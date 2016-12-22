<?php
/*********************************************************************************
 *       Filename: Editar Empleados.php
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
$filename = "Editar Empleados.php";
$template_filename = "Editar Empleados.html";
//===============================



//===============================
// Insertar Empleados PageSecurity begin
check_security(3);
// Insertar Empleados PageSecurity end
//===============================

//===============================
// Insertar Empleados Open Event begin
// Insertar Empleados Open Event end
//===============================

//===============================
// Insertar Empleados OpenAnyPage Event start
// Insertar Empleados OpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

// Insertar Empleados Show begin

//===============================
// Perform the form's action
//-------------------------------
// Initialize error variables
//-------------------------------
$sEmpleadoErr = "";

//-------------------------------
// Select the FormAction
//-------------------------------


switch ($sForm) {
  case "Empleado":
    Empleados_action($sAction);
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
Empleados_show();

//-------------------------------
// Process page templates
//-------------------------------
//$tpl->parse("Header", false);
//-------------------------------
// Output the page to the browser
//-------------------------------
   $tpl->pparse("main", false);
// Insertar Empleados Show end

//===============================
// Insertar Empleados Close Event begin
// Insertar Empleados Close Event end
//===============================
//********************************************************************************


//===============================
// Action of the Record Form
//-------------------------------
function Empleados_action($sAction)
{
//-------------------------------
// Initialize variables  
//-------------------------------
  global $db;
  global $tpl;
  global $sForm;
  global $sEmpleadoErr;
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
// Empleados Action begin
//-------------------------------
  $sActionFileName = "Inicio Empleados.php";

//-------------------------------
// CANCEL action
//-------------------------------
  if($sAction == "cancel")
  {

//-------------------------------
// Empleadoss BeforeCancel Event begin
// Empleadoss BeforeCancel Event end
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
    $pPK_id_Empleado = get_param("PK_id_Empleado");
    if( !strlen($pPK_id_Empleado)) return;
    $sWhere = "id_Empleado=" . tosql($pPK_id_Empleado, "Number");
  }
//-------------------------------


//-------------------------------
// Load all form fields into variables
//-------------------------------
  $fldnombre = get_param("nombre");
  $fldapaterno = get_param("apaterno");
  $fldamaterno = get_param("amaterno");
  $fldpuesto = get_param("puesto");
  $flddireccion_calle = get_param("direccion_calle");
  $flddireccion_colonia = get_param("direccion_colonia");
  $flddireccion_delegacion = get_param("direccion_delegacion");
  $flddireccion_estado = get_param("direccion_estado");
  $flddireccion_cp = get_param("direccion_cp");
  $fldtelefono_casa = get_param("telefono_casa");
  $fldtelefono_celular = get_param("telefono_celular");
  $fldtelefono_trabajo = get_param("telefono_trabajo");
  $fldtelefono_fax = get_param("telefono_fax");
  $fldemail = get_param("email");
  $fldlogin = get_param("login");
  $fldpassword = get_param("password");
  $fldpassword2 = get_param("password2");
  $fldaccess_level = get_param("access_level");

//-------------------------------
// Validate fields
//-------------------------------
  if($sAction == "insert" || $sAction == "update") 
  {
//-------------------------------
// Empleadoss Check Event begin
// Empleadoss Check Event end
//-------------------------------
    if(!strlen($fldnombre))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: No se puede dejar el campo 'Nombre(s)' en blanco.") . "<br>";       

    if(!strlen($fldapaterno))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: No se puede dejar el campo 'Apellido Paterno' en blanco.") . "<br>";       

    if(!strlen($fldamaterno))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: No se puede dejar el campo 'Apellido Materno' en blanco.") . "<br>";       

    if(!strlen($fldpuesto))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: No se puede dejar el campo 'Puesto' en blanco.") . "<br>";       

    if(!strlen($flddireccion_calle))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: No se puede dejar el campo 'Direccion (Calle y Numero)' en blanco.") . "<br>";       

    if(!strlen($flddireccion_colonia))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: No se puede dejar el campo 'Direccion (Colonia)' en blanco.") . "<br>";       

    if(!strlen($flddireccion_delegacion))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: No se puede dejar el campo 'Direccion (Delegacion)' en blanco.") . "<br>";       

    if(!strlen($flddireccion_estado))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: No se puede dejar el campo 'Direccion (Estado)' en blanco.") . "<br>";       

    if(!strlen($flddireccion_cp))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: No se puede dejar el campo 'Codigo Postal' en blanco.") . "<br>";       

    if(!ereg("[0-9]{5}",$flddireccion_cp) && strlen($flddireccion_cp))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: El campo 'Codigo Postal' contiene caracteres inválidos.") . "<br>";       

    if(!ereg("[0-9,-,(,)]{1,20}",$fldtelefono_casa) && strlen($fldtelefono_casa))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: El campo 'Telefono Casa' contiene caracteres inválidos.") . "<br>";       

    if(!ereg("[0-9,-,(,)]{1,20}",$fldtelefono_celular) && strlen($fldtelefono_celular))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: El campo 'Telefono Celular' contiene caracteres inválidos.") . "<br>";       

    if(!ereg("[0-9,-,(,)]{1,20}",$fldtelefono_trabajo) && strlen($fldtelefono_trabajo))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: El campo 'Telefono Trabajo' contiene caracteres inválidos.") . "<br>";       

    if(!ereg("[0-9,-,(,)]{1,20}",$fldtelefono_fax) && strlen($fldtelefono_fax))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: El campo 'Fax' contiene caracteres inválidos.") . "<br>";       

    if(!ereg("([0-9,a-z,A-Z]{1,1})([0-9,a-z,A-Z,_,-,.]{0,})([@]{1})([0-9,a-z,A-Z,.,-,_]{1,})([.]{1})([0-9,a-z,A-Z,.,-,_]{1,})",$fldemail))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: El campo 'Email' esta mal formado.") . "<br>";       

    if(!ereg("[0-9,a-z,A-Z]{4,}",$fldlogin))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: El campo 'Login' debe estar compuesto de al menos 4 caracteres alfanumericos.") . "<br>";       

    if($sAction=="insert"){
    $iSQL="SELECT * FROM EMPLEADOS WHERE ESTATUS=1 and LOGIN=" . tosql($fldlogin, "Text");
    $db->query($iSQL);
    $exists=$db->next_record();
    if($exists)
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: Ya existe el login " . $fldlogin ." en la base de datos, seleccione otro.") . "<br>";       
    }

    if(!ereg("[0-9,a-z,A-Z]{6,}",$fldpassword))
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: El campo 'Password' debe estar compuesto de al menos 6 caracteres alfanumericos.") . "<br>";       
    
    if($fldpassword != $fldpassword2)
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: El campo 'Password' y su confirmacion no coinciden.") . "<br>";       

    if($fldaccess_level == -1)
       $sEmpleadoErr=$sEmpleadoErr . tohtml("Error: Seleccione el nivel de acceso del empleado.") . "<br>";       

//   echo($sEmpleadoErr);

    if(strlen($sEmpleadoErr)) return;
  }
//-------------------------------


//-------------------------------
// Create SQL statement
//-------------------------------
  switch(strtolower($sAction)) 
  {
    case "insert":
//-------------------------------
// Empleadoss Insert Event begin
// Empleadoss Insert Event end
//-------------------------------

        $sSQL = "insert into Empleados (" . 
          "nombres,apaterno,amaterno,puesto,telefono_casa," .
          "telefono_celular,telefono_trabajo," .
          "telefono_fax,direccion_calle,direccion_colonia, " .
          "direccion_delegacion,direccion_estado,direccion_cp, " .
          "email,login,password,nivel_acceso)" . 
          " values (" . 
          tosql($fldnombre, "Text") . ","  .
          tosql($fldapaterno, "Text") . ","  .
          tosql($fldamaterno, "Text") . ","  .
          tosql($fldpuesto, "Text") . "," .
          tosql($fldtelefono_casa, "Text") . "," .
	  tosql($fldtelefono_celular, "Text") . "," .
          tosql($fldtelefono_trabajo, "Text") . "," .
          tosql($fldtelefono_fax, "Text") . "," .
          tosql($flddireccion_calle, "Text") . ","  .
          tosql($flddireccion_colonia, "Text") . ","  .
          tosql($flddireccion_delegacion, "Text") . ","  .
          tosql($flddireccion_estado, "Text") . ","  .
          tosql($flddireccion_cp, "Text") . ","  .
          tosql($fldemail, "Text") . "," .
          tosql($fldlogin, "Text") . "," .
          tosql($fldpassword, "Text") . "," .
          tosql($fldaccess_level, "Number") . 
          ")";
    break;
    case "update":

//-------------------------------
// Empleadoss Update Event begin
// Empleadoss Update Event end
//-------------------------------
        $sSQL = "update Empleados set " .
          "nombres=" . tosql($fldnombre, "Text") . "," .
          "apaterno=" . tosql($fldapaterno, "Text") . "," .
          "amaterno=" . tosql($fldamaterno, "Text") . "," .
          "puesto=" . tosql($fldpuesto, "Text") . "," .
          "direccion_calle=" . tosql($flddireccion_calle, "Text") . ","  .
          "direccion_colonia=" . tosql($flddireccion_colonia, "Text") . ","  .
          "direccion_delegacion=" . tosql($flddireccion_delegacion, "Text") . ","  .
          "direccion_estado=" . tosql($flddireccion_estado, "Text") . ","  .
          "direccion_cp=" . tosql($flddireccion_cp, "Text") . ","  .
          "telefono_casa=" . tosql($fldtelefono_casa, "Text") . "," .
          "telefono_celular=" . tosql($fldtelefono_celular, "Text") . "," .
          "telefono_trabajo=" . tosql($fldtelefono_trabajo, "Text") . "," .
          "telefono_fax=" . tosql($fldtelefono_fax, "Text") . "," .
          "email=" . tosql($fldemail, "Text") . "," .
          "login=" . tosql($fldlogin, "Text") . "," .
          "password=" . tosql($fldpassword, "Text") . "," .
          "nivel_acceso=" . tosql($fldaccess_level, "Text");

        $sSQL .= " where " . $sWhere;
    break;
    case "delete":
//-------------------------------
// Empleadoss Delete Event begin
// Empleadoss Delete Event end
//-------------------------------
        $sSQL = "Update empleados set estatus=0 where " . $sWhere;
    break;
  }
//-------------------------------
//-------------------------------
// Empleados BeforeExecute Event begin
// Empleados BeforeExecute Event end
//-------------------------------

//-------------------------------
// Execute SQL statement
//-------------------------------
  if(strlen($sEmpleadoErr)) return;
  if($bExecSQL)
    $db->query($sSQL);
  header("Location: " . $sActionFileName . $TransitParams);
  exit;

//-------------------------------
// Empleadoss Action end
//-------------------------------
}

//===============================
// Display Record Form
//-------------------------------
function Empleados_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sEmpleadoErr;
  
  $fldid_Empleados = "";
  $fldnombre = "";
  $fldapaterno = "";
  $fldamaterno = "";
  $fldpuesto= "";
  $flddireccion_calle = "";
  $flddireccion_colonia = "";
  $flddireccion_delegacion = "";
  $flddireccion_estado = "";
  $flddireccion_cp = "";
  $fldtelefono_casa= "";
  $fldtelefono_fax= "";
  $fldtelefono_celular= "";
  $fldtelefono_trabajo= "";
  $fldemail="";
  $fldlogin="";
  $fldpassword="";
  $fldaccess_level="";

//-------------------------------
// Empleadoss Show begin
//-------------------------------
  $sFormTitle = "Empleados";
  $sWhere = "";
  $bPK = true;
//-------------------------------
// Load primary key and form parameters
//-------------------------------
  if($sEmpleadoErr == "")
  {
    $pid_Empleado=get_param("id_empleado");
    $fldnombre = get_param("nombre");
    $fldapaterno = get_param("apaterno");
    $fldamaterno = get_param("amaterno");
    $fldpuesto = get_param("puesto");
    $flddireccion_calle = get_param("direccion_calle");
    $flddireccion_colonia = get_param("direccion_colonia");
    $flddireccion_delegacion = get_param("direccion_delegacion");
    $flddireccion_estado = get_param("direccion_estado");
    $flddireccion_cp = get_param("direccion_cp");
    $fldtelefono_casa = get_param("telefono_casa");
    $fldtelefono_celular = get_param("telefono_celular");
    $fldtelefono_trabajo = get_param("telefono_trabajo");
    $fldtelefono_fax = get_param("telefono_fax");
    $fldemail = get_param("email");
    $fldlogin = get_param("login");
    $fldpassword = get_param("password");
    $fldpassword2 = get_param("password2");
    $fldaccess_level = get_param("access_level");
   
    $tpl->set_var("EmpleadoError", "");
  }
  else
  {
    $pid_Empleado=strip(get_param("id_empleado"));
    $fldnombre = strip(get_param("nombre"));
    $fldapaterno = strip(get_param("apaterno"));
    $fldamaterno = strip(get_param("amaterno"));
    $fldpuesto = strip(get_param("puesto"));
    $flddireccion_calle = strip(get_param("direccion_calle"));
    $flddireccion_colonia = strip(get_param("direccion_colonia"));
    $flddireccion_delegacion = strip(get_param("direccion_delegacion"));
    $flddireccion_estado = strip(get_param("direccion_estado"));
    $flddireccion_cp = strip(get_param("direccion_cp"));
    $fldtelefono_casa = strip(get_param("telefono_casa"));
    $fldtelefono_celular = strip(get_param("telefono_celular"));
    $fldtelefono_trabajo = strip(get_param("telefono_trabajo"));
    $fldtelefono_fax = strip(get_param("telefono_fax"));
    $fldemail = strip(get_param("email"));
    $fldlogin = strip(get_param("login"));
    $fldpassword = strip(get_param("password"));
    $fldpassword2 = strip(get_param("password2"));
    $fldaccess_level = strip(get_param("access_level"));

    $tpl->set_var("sEmpleadoErr", $sEmpleadoErr);
    $tpl->set_var("FormTitle", $sFormTitle);
    $tpl->parse("EmpleadoError", false);
  }
//-------------------------------

//-------------------------------
// Load all form fields

//-------------------------------

//-------------------------------
// Build WHERE statement
//-------------------------------
  
  if( !strlen($pid_Empleado)) $bPK = false;
  
  $sWhere .= "id_Empleado=" . tosql($pid_Empleado, "Number");
  $tpl->set_var("PK_id_Empleado", $pid_Empleado);
//-------------------------------
//-------------------------------
// Empleadoss Open Event begin
// Empleadoss Open Event end
//-------------------------------

  $tpl->set_var("FormTitle", $sFormTitle);

//-------------------------------
// Build SQL statement and execute query
//-------------------------------
  $sSQL = "select * from Empleados where " . $sWhere;
  // Execute SQL statement
  $db->query($sSQL);
  $bIsUpdateMode = ($bPK && !($sAction == "insert" && $sForm == "Empleado") && $db->next_record());
//-------------------------------

//-------------------------------
// Load all fields into variables from recordset or input parameters
//-------------------------------
  if($bIsUpdateMode)
  {
    $fldid_Empleados = $db->f("id_Empleado");
//-------------------------------
// Load data from recordset when form displayed first time
//-------------------------------
    if($sEmpleadoErr == "") 
    {
      $fldnombre = $db->f("nombres");
      $fldamaterno = $db->f("amaterno");
      $fldapaterno = $db->f("apaterno");
      $fldpuesto = $db->f("puesto");
      $flddireccion_calle = $db->f("direccion_calle");
      $flddireccion_colonia = $db->f("direccion_colonia");
      $flddireccion_delegacion = $db->f("direccion_delegacion");
      $flddireccion_estado = $db->f("direccion_estado");
      $flddireccion_cp = $db->f("direccion_cp");
      $fldtelefono_casa = $db->f("telefono_casa");
      $fldtelefono_celular = $db->f("telefono_celular");
      $fldtelefono_trabajo = $db->f("telefono_trabajo");
      $fldtelefono_fax = $db->f("telefono_fax");
      $fldemail = $db->f("email");
      $fldlogin = $db->f("login");
      $fldpassword = $db->f("password");
      $fldaccess_level = $db->f("nivel_acceso");
    }
    $tpl->set_var("EmpleadoInsert", "");
    $tpl->parse("EmpleadoEdit", false);
//-------------------------------
// Empleadoss ShowEdit Event begin
// Empleadoss ShowEdit Event end
//-------------------------------
  }
  else
  {
    if($sEmpleadoErr == "")
    {
      $fldid_Empleado = tohtml(get_param("id_Empleado"));
    }
    $tpl->set_var("EmpleadoEdit", "");
    $tpl->parse("EmpleadoInsert", false);
//-------------------------------
// Empleadoss ShowInsert Event begin
// Empleadoss ShowInsert Event end
//-------------------------------
  }
  $tpl->parse("EmpleadoCancel", false);
//-------------------------------
// Empleadoss Show Event begin
// Empleadoss Show Event end
//-------------------------------

//-------------------------------
// Show form field
//-------------------------------
    $tpl->set_var("id_Empleados", tohtml($fldid_Empleados));
    $tpl->set_var("nombre", tohtml($fldnombre));
    $tpl->set_var("apaterno", tohtml($fldapaterno));
    $tpl->set_var("amaterno", tohtml($fldamaterno));
    $tpl->set_var("puesto", tohtml($fldpuesto));
    $tpl->set_var("direccion_calle", tohtml($flddireccion_calle));
    $tpl->set_var("direccion_colonia", tohtml($flddireccion_colonia));
    $tpl->set_var("direccion_delegacion", tohtml($flddireccion_delegacion));
    $tpl->set_var("direccion_estado", tohtml($flddireccion_estado));
    $tpl->set_var("direccion_cp", tohtml($flddireccion_cp));
    $tpl->set_var("telefono_casa", tohtml($fldtelefono_casa));
    $tpl->set_var("telefono_celular", tohtml($fldtelefono_celular));
    $tpl->set_var("telefono_trabajo", tohtml($fldtelefono_trabajo));
    $tpl->set_var("telefono_fax", tohtml($fldtelefono_fax));
    $tpl->set_var("id_cliente", tohtml($fldid_cliente));
    $tpl->set_var("email", tohtml($fldemail));
    $tpl->set_var("login", tohtml($fldlogin));
    $tpl->set_var("password", tohtml($fldpassword));
    $tpl->set_var("password2", tohtml($fldpassword));
    $fldaccess_level=genselect_fromcat("Nivel de Acceso","access_level",$fldaccess_level);
    $tpl->set_var("access_level", $fldaccess_level);

    $tpl->parse("FormEmpleado", false);

//-------------------------------
// Empleadoss Close Event begin
// Empleadoss Close Event end
//-------------------------------

//-------------------------------
// Empleadoss Show end
//-------------------------------
}
//===============================
?>
