<?php
/*********************************************************************************
 *       factname: Listar facturas.php
 *       Proyecto Coca Consultores
 *       Adrian Bisiacchi 
 *       PHP 4.0 & templates Escrito el 10/04/2002
 *********************************************************************************/

//-------------------------------
// Tabla facturasCustomIncludes begin

include ("./common.php");

// Tabla facturasCustomIncludes end
//-------------------------------


//-------------------------------
// Tabla facturasCustomFunctions begin

function avoid_nulls($string)
{
  if(!strlen($string))
    return "????";
  else
    return $string;
}

// Tabla facturasCustomFunctions end
//-------------------------------

session_start();

//===============================
// Save Page and fact Name available into variables
//-------------------------------
$filename = "Inicio facturas.php";
$template_filename = "Inicio facturas.html";
//===============================



//===============================
// Inicio facturasPageSecurity begin
check_security(2);
// Inicio facturasPageSecurity end
//===============================

//===============================
// Inicio facturasOpen Event begin
// Inicio facturasOpen Event end
//===============================

//===============================
// Inicio facturasOpenAnyPage Event start
// Inicio facturasOpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

// Inicio facturasShow begin

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
//-------------------------------
$tpl->set_var("FileName", $filename);



//-------------------------------
// Step through each form
//-------------------------------
facts_show();

//-------------------------------
// Process page templates
//-------------------------------
//-------------------------------
// Output the page to the browser
//-------------------------------
$tpl->pparse("main", false);
// Inicio facturasShow end

//===============================
// Inicio facturasClose Event begin
// Inicio facturasClose Event end
//===============================
//********************************************************************************


//===============================
// Display Grid Form
//-------------------------------
function facts_show()
{
//-------------------------------
// Initialize variables  
//-------------------------------
  
  global $tpl;
  global $db;
  global $sfactsErr;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $sFormTitle = "Facturas";
  $HasParam = false;
  $iRecordsPerPage = 20;
  $iCounter = 0;
  $iPage = 0;
  $bEof = false;
  $iSort = "";
  $iSorted = "";
  $sDirection = "";
  $sSortParams = "";
  $sActionFileName = "Editar Facturas.php";


  $tpl->set_var("TransitParams", "id_cliente_nav=" . get_param("id_cliente_nav") . "&" );
  $tpl->set_var("FormParams", "id_cliente_nav=" . get_param("id_cliente_nav") . "&");

  if(strlen(get_param("Err")))
      $sfactsErr=strip(get_param("Err"));
  else 
      $sfactsErr="";

//-------------------------------
// Build ORDER BY statement
//-------------------------------
  $iSort = get_param("Formfacts_Sorting");
  $iSorted = get_param("Formfacts_Sorted");
    if($iSort == $iSorted)
    {
      $tpl->set_var("Form_Sorting", "");
      $sDirection = " DESC";
      $sSortParams = "Formfacts_Sorting=" . $iSort . "&Formfacts_Sorted=" . $iSort . "&";
    }
    else
    {
      $tpl->set_var("Form_Sorting", $iSort);
      $sDirection = " ASC";
      $sSortParams = "Formfacts_Sorting=" . $iSort . "&Formfacts_Sorted=" . "&";
    }
    switch($iSort){
    	case 1: 
		$sOrder = " order by f.num_factura" . $sDirection;
		break;
    	case 2: 
		$sOrder = " order by f.fecha" . $sDirection;
		break;
    	case 3: 
		$sOrder = " order by f.descripcion" . $sDirection;
		break;
    	case 4: 
		$sOrder = " order by f.monto" . $sDirection;
		break;
    	case 5: 
		$sOrder = " order by f.pagada" . $sDirection;
		break;
    }

//-------------------------------
// Build base SQL statement
//-------------------------------
  $sSQL = "select f.id_factura as f_id_factura, " . 
   "f.num_factura as f_num_factura " . " ," .
   "f.fecha as f_fecha " . " ," .   
   "f.descripcion as f_descripcion " . " ," .   
   "f.estatus as f_estatus " . " ," .   
   "f.monto as f_monto " .
   " from facturas f ";
//-------------------------------

$sWhere=" WHERE id_cliente=" . tosql(get_param("id_cliente_nav"),"Number");

//-------------------------------
// facts Open Event begin
// facts Open Event end
//-------------------------------

//-------------------------------
// Assemble full SQL statement
//-------------------------------
  $sSQL .= $sWhere . $sOrder;
//  echo($sSQL);
//-------------------------------

  $tpl->set_var("FormTitle", $sFormTitle);
  $tpl->set_var("id_cliente_nav",get_param("id_cliente_nav"));

  if(strlen($sfactsErr)){
      $tpl->set_var("errMsg",$sfactsErr);
      $tpl->parse(factsErr,false);}
  else
      $tpl->set_var("factsErr","");


//-------------------------------
// Process the link to the record page
//-------------------------------
  $tpl->set_var("FormAction", $sActionFileName);
//-------------------------------

//-------------------------------
// Process the parameters for sorting
//-------------------------------
  $tpl->set_var("SortParams", $sSortParams);
//-------------------------------

//-------------------------------
// Execute SQL statement
//-------------------------------
  $db->query($sSQL);
  $next_record = $db->next_record();
//-------------------------------
// Process factty recordset
//-------------------------------
  if(!$next_record)
  {
    $tpl->set_var("DListfacts", "");
    $tpl->parse("factsNoRecords", false);
    $tpl->set_var("factsNavigator", "");
    $tpl->parse("Formfacts", false);
    return;
  }
  
//-------------------------------
//-------------------------------
// Initialize page counter and records per page
//-------------------------------
  $iRecordsPerPage = 15;
  $iCounter = 0;
//-------------------------------

//-------------------------------
// Process page scroller
//-------------------------------
  $iPage = get_param("Formfacts_Page");
  if(!strlen($iPage)) $iPage = 1; else $iPage = intval($iPage);

  if(($iPage - 1) * $iRecordsPerPage != 0)
  {
    do
    {
      $iCounter++;
    } while ($iCounter < ($iPage - 1) * $iRecordsPerPage && $db->next_record());
    $next_record = $db->next_record();
  }

  $iCounter = 0;
//-------------------------------

//-------------------------------
// Display grid based on recordset
//-------------------------------
  while($next_record  && $iCounter < $iRecordsPerPage)
  {
//-------------------------------
// Create field variables based on database fields
//-------------------------------
    $fldfact_id_URLLink = "Editar facturas.php";
    $fldfact_id_fact_id = $db->f("f_id_factura");
    $fldfact_id = $db->f("f_id_factura");
    $fldfact_edit = "Editar";    
    $fldfact_num_factura = avoid_nulls($db->f("f_num_factura"));
    $fldfact_fecha = avoid_nulls($db->f("f_fecha"));
    $fldfact_descripcion = avoid_nulls($db->f("f_descripcion"));
    $fldfact_monto = avoid_nulls($db->f("f_monto"));
    $fldfact_estatus = avoid_nulls($db->f("f_estatus"));
    $next_record = $db->next_record();
    
//-------------------------------
// recs Show begin
//-------------------------------

//-------------------------------
// facts Show Event begin
// facts Show Event end
//-------------------------------

//-------------------------------
// Replace template fields with database values
//-------------------------------
    
      $tpl->set_var("fact_id", tohtml($fldfact_id));
      $tpl->set_var("fact_edit", tohtml($fldfact_edit));
      $tpl->set_var("fact_id_URLLink", $fldfact_id_URLLink);
      $tpl->set_var("Prmfact_id_fact_id", urlencode($fldfact_id_fact_id));
      $tpl->set_var("num_factura", tohtml($fldfact_num_factura));
      $tpl->set_var("fecha", tohtml($fldfact_fecha));
      $tpl->set_var("descripcion", tohtml($fldfact_descripcion));
      $tpl->set_var("monto", tohtml($fldfact_monto));

      if($fldfact_estatus==0)
        $tpl->set_var("pagada","No");
      else
        $tpl->set_var("pagada","Si");

      $tpl->set_var("id_Factura",$fldfact_id);
//-------------------------------------------
//Dynamically control BGCOLOR and FONT-WEIGHT
//-------------------------------------------

      $tpl->set_var("color",$fldcolor);

      $tpl->parse("DListfacts", true);
//-------------------------------
// facts Show end
//-------------------------------

//-------------------------------
// Move to the next factord and increase record counter
//-------------------------------
    
    $iCounter++;
  }

  // facts Navigation begin
  $bEof = $next_record;
  // Parse Navigator
  if(!$bEof && $iPage == 1)
    $tpl->set_var("factsNavigator", "");
  else 
  {
    if(!$bEof)
      $tpl->set_var("factsNavigatorLastPage", "_");
    else
      $tpl->set_var("NextPage", ($iPage + 1));
    if($iPage == 1)
      $tpl->set_var("factsNavigatorFirstPage", "_");
    else
      $tpl->set_var("PrevPage", ($iPage - 1));
    $tpl->set_var("factsCurrentPage", $iPage);
    $tpl->parse("factsNavigator", false);
  }

//-------------------------------
// facts Navigation end
//-------------------------------

//-------------------------------
// Finish form processing
//-------------------------------
  $tpl->set_var( "factsNoRecords", "");

  $tpl->parse( "Formfacts", false);
//-------------------------------
// facts Close Event begin
// facts Close Event end
//-------------------------------
}
//===============================

?>