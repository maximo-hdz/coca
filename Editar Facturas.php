<?php
/*********************************************************************************
 *       Filename: Editar Facturas.php
 *       Proyecto COCA Consultores
 *       Adrian Bisiacchi
 *       PHP 4.0 & templates Escrito el 10/04/2002
 *********************************************************************************/

//-------------------------------
// Insertar Facturas CustomIncludes begin

include ("./common.php");

// Insertar Facturas CustomIncludes end
//-------------------------------

session_start();

//===============================
// Save Page and File Name available into variables
//-------------------------------
$filename = "Editar Facturas.php";
$template_filename = "Editar Facturas.html";
//===============================



//===============================
// Insertar Facturas PageSecurity begin
check_security(2);
// Insertar Facturas PageSecurity end
//===============================

//===============================
// Insertar Facturas Open Event begin
// Insertar Facturas Open Event end
//===============================

//===============================
// Insertar Facturas OpenAnyPage Event start
// Insertar Facturas OpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

// Insertar Facturas Show begin

//===============================
// Perform the form's action
//-------------------------------
// Initialize error variables
//-------------------------------
$sFacturaErr = "";

//-------------------------------
// Select the FormAction
//-------------------------------


switch ($sForm) {
  case "Factura":
    Facturas_action($sAction);
  break;
}
//===============================

//===============================
// Display page
//-------------------------------
// Load HTML template for this page
//-------------------------------
$tpl = new template($app_path);
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
Facturas_show();

//-------------------------------
// Process page templates
//-------------------------------
//$tpl->parse("Header", false);
//-------------------------------
// Output the page to the browser
//-------------------------------
   $tpl->pparse("main", false);
// Insertar Facturas Show end

//===============================
// Insertar Facturas Close Event begin
// Insertar Facturas Close Event end
//===============================
//********************************************************************************


//===============================
// Action of the Record Form
//-------------------------------
function Facturas_action($sAction)
{
//-------------------------------
// Initialize variables  
//-------------------------------
  global $db;
  global $tpl;
  global $sForm;
  global $sFacturaErr;
  $bExecSQL = true;
  $sActionFileName = "";
  $sWhere = "";
  $bErr = false;
  $pPKcon_id = "";
  $fldnombre = "";
  $TransitParams="";

//-------------------------------
// Facturas Action begin
//-------------------------------
  $sActionFileName = "Inicio Facturas.php";

//-------------------------------
// CANCEL action
//-------------------------------
  if($sAction == "cancel")
  {

//-------------------------------
// Facturass BeforeCancel Event begin
// Facturass BeforeCancel Event end
//-------------------------------
    header("Location: " . $sActionFileName . "?id_cliente_nav=" . get_param("id_cliente_nav"));
    exit;
  }
//-------------------------------


//-------------------------------
// Build WHERE statement
//-------------------------------
  if($sAction == "update" || $sAction == "delete") 
  {
    $pPK_id_Factura = get_param("PK_id_Factura");
    if( !strlen($pPK_id_Factura)) return;
    $sWhere = "id_Factura=" . tosql($pPK_id_Factura, "Number");
  }
//-------------------------------


//-------------------------------
// Load all form fields into variables
//-------------------------------
  $fldnum_factura = get_param("num_factura");
  $fldfecha = get_param("fecha");
  $flddescripcion = get_param("descripcion");
  $fldmonto = get_param("monto");
  $fldpagada = get_param("pagada");
  $fldid_cliente = get_param("id_cliente_nav");

  if(strlen($fldpagada))
    $fldestatus="1";
  else
    $fldestatus="0";

//-------------------------------
// Validate fields
//-------------------------------
  if($sAction == "insert" || $sAction == "update") 
  {
//-------------------------------
// Facturass Check Event begin
// Facturass Check Event end
//-------------------------------
    if(!strlen($fldnum_factura))
       $sFacturaErr=$sFacturaErr . tohtml("Error: No se puede dejar el campo 'Numero de Factura' en blanco.") . "<br>";       
    if(!is_numeric($fldnum_factura))
       $sFacturaErr=$sFacturaErr . tohtml("Error: El Numero de Factura debe ser numerico.") . "<br>";       
     
    if(!is_date($fldfecha))
       $sFacturaErr=$sFacturaErr . tohtml("Error: La fecha de la factura es invalida") . "<br>";       

    if(!strlen($flddescripcion))
       $sFacturaErr=$sFacturaErr . tohtml("Error: No se puede dejar el campo 'Descripcion' en blanco.") . "<br>";       

    if(!strlen($fldmonto))
       $sFacturaErr=$sFacturaErr . tohtml("Error: No se puede dejar el campo 'Monto' en blanco.") . "<br>";       
    if(!is_numeric($fldmonto))
       $sFacturaErr=$sFacturaErr . tohtml("Error: El monto debe ser numerico.") . "<br>";       

    if(strlen($sFacturaErr)) return;
  }
//-------------------------------


//-------------------------------
// Create SQL statement
//-------------------------------
  switch(strtolower($sAction)) 
  {
    case "insert":
//-------------------------------
// Facturass Insert Event begin
// Facturass Insert Event end
//-------------------------------

        $sSQL = "insert into Facturas (" . 
          "num_factura,id_cliente,fecha,descripcion,monto," .
          "estatus)" . 
          " values (" . 
          tosql($fldnum_factura, "Number") . ","  .
          tosql($fldid_cliente, "Number") . ","  .
          tosql($fldfecha, "Date") . ","  .
          tosql($flddescripcion, "Text") . ","  .
          tosql($fldmonto, "Number") . ","  .
          tosql($fldestatus, "Number") .
          ")";
    break;
    case "update":

//-------------------------------
// Facturass Update Event begin
// Facturass Update Event end
//-------------------------------
        $sSQL = "update Facturas set " .
          "num_factura=" . tosql($fldnum_factura, "Number") . "," .
          "id_cliente=" . tosql($fldid_cliente, "Number") . "," .
          "fecha=" . tosql($fldfecha, "Date") . "," .
          "descripcion=" . tosql($flddescripcion, "Text") . "," .
          "monto=" . tosql($fldmonto, "Number") . "," .
          "estatus=" . tosql($fldestatus, "Number");

        $sSQL .= " where " . $sWhere;
    break;
    case "delete":
//-------------------------------
// Facturass Delete Event begin
// Facturass Delete Event end
//-------------------------------
        $sSQL = "DELETE FROM Facturas where " . $sWhere;
    break;
  }
//-------------------------------
//-------------------------------
// Facturas BeforeExecute Event begin
// Facturas BeforeExecute Event end
//-------------------------------

//-------------------------------
// Execute SQL statement
//-------------------------------
  if(strlen($sFacturaErr)) return;
  if($bExecSQL)
    $db->query($sSQL);
    header("Location: " . $sActionFileName . "?id_cliente_nav=" . get_param("id_cliente_nav"));
  exit;

//-------------------------------
// Facturass Action end
//-------------------------------
}

//===============================
// Display Record Form
//-------------------------------
function Facturas_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sFacturaErr;
  
  $fldnum_factura = "";
  $fldfecha = "";
  $flddescripcion = "";
  $fldmonto = "";
  $fldpagada = "";
  $fldid_cliente = "";

//-------------------------------
// Facturass Show begin
//-------------------------------
  $sFormTitle = "Facturas";
  $sWhere = "";
  $bPK = true;
//-------------------------------
// Load primary key and form parameters
//-------------------------------
  if($sFacturaErr == "")
  {
  $pid_Factura=get_param("id_Factura");
  $fldnum_factura = get_param("num_factura");
  $fldfecha = get_param("fecha");
  $flddescripcion = get_param("descripcion");
  $fldmonto = get_param("monto");
  $fldpagada = get_param("pagada");
  $fldid_cliente = get_param("id_cliente_nav");
   
    $tpl->set_var("FacturaError", "");
  }
  else
  {
  $pid_Factura=strip(get_param("id_Factura"));
  $fldnum_factura = strip(get_param("num_factura"));
  $fldfecha = strip(get_param("fecha"));
  $flddescripcion = strip(get_param("descripcion"));
  $fldmonto = strip(get_param("monto"));
  $fldpagada = strip(get_param("pagada"));
  $fldid_cliente = strip(get_param("id_cliente_nav"));

    $tpl->set_var("sFacturaErr", $sFacturaErr);
    $tpl->set_var("FormTitle", $sFormTitle);
    $tpl->parse("FacturaError", false);
  }
//-------------------------------

//-------------------------------
// Load all form fields

//-------------------------------

//-------------------------------
// Build WHERE statement
//-------------------------------
  
  if( !strlen($pid_Factura)) $bPK = false;
  
  $sWhere .= "id_Factura=" . tosql($pid_Factura, "Number");
  $tpl->set_var("PK_id_Factura", $pid_Factura);
//-------------------------------
//-------------------------------
// Facturass Open Event begin
// Facturass Open Event end
//-------------------------------

  $tpl->set_var("FormTitle", $sFormTitle);

//-------------------------------
// Build SQL statement and execute query
//-------------------------------
  $sSQL = "select * from Facturas where " . $sWhere;
  // Execute SQL statement
  $db->query($sSQL);
  $bIsUpdateMode = ($bPK && !($sAction == "insert" && $sForm == "Factura") && $db->next_record());
//-------------------------------

//-------------------------------
// Load all fields into variables from recordset or input parameters
//-------------------------------
  if($bIsUpdateMode)
  {
    $fldid_Facturas = $db->f("id_Factura");
//-------------------------------
// Load data from recordset when form displayed first time
//-------------------------------
    if($sFacturaErr == "") 
    {
     $fldnum_factura = $db->f("num_factura");
     $fldfecha = $db->f("fecha");
     $flddescripcion = $db->f("descripcion");
     $fldmonto = $db->f("monto");
     $fldpagada = $db->f("estatus");
     $fldid_cliente = $db->f("id_cliente_nav");
    }
    $tpl->set_var("FacturaInsert", "");
    $tpl->parse("FacturaEdit", false);
//-------------------------------
// Facturass ShowEdit Event begin
// Facturass ShowEdit Event end
//-------------------------------
  }
  else
  {
    if($sFacturaErr == "")
    {
      $fldid_Factura = tohtml(get_param("id_Factura"));
    }
    $tpl->set_var("FacturaEdit", "");
    $tpl->parse("FacturaInsert", false);
//-------------------------------
// Facturass ShowInsert Event begin
// Facturass ShowInsert Event end
//-------------------------------
  }
  $tpl->parse("FacturaCancel", false);
//-------------------------------
// Facturass Show Event begin
// Facturass Show Event end
//-------------------------------

//-------------------------------
// Show form field
//-------------------------------

     $tpl->set_var("num_factura",tohtml($fldnum_factura));
     $tpl->set_var("fecha",tohtml($fldfecha));
     $tpl->set_var("id_cliente",tohtml($fldid_cliente));
     $tpl->set_var("fecha",tohtml($fldfecha));
     $tpl->set_var("descripcion",tohtml($flddescripcion));
     $tpl->set_var("monto",tohtml($fldmonto));
     if(strlen($fldpagada))
        $tpl->set_var("pagada_checked","");
     else
        $tpl->set_var("pagada_checked","CHECKED");
     $tpl->set_var("id_cliente_nav",get_param("id_cliente_nav"));     

    $tpl->parse("FormFactura", false);

//-------------------------------
// Facturass Close Event begin
// Facturass Close Event end
//-------------------------------

//-------------------------------
// Facturass Show end
//-------------------------------
}
//===============================
?>
