<?php
/*********************************************************************************
 *       Filename: Listar Recordatorios.php
 *       Proyecto Coca Consultore
 *       Adrian Bisiacchi 
 *       PHP 4.0 & Templates Escrito el 10/04/2002
 *********************************************************************************/

//-------------------------------
// Tabla RecordatoriosCustomIncludes begin

include ("./common.php");

// Tabla RecordatoriosCustomIncludes end
//-------------------------------


//-------------------------------
// Tabla RecordatoriosCustomFunctions begin

function avoid_nulls($string)
{
  if(!strlen($string))
    return "????";
  else
    return $string;
}

// Tabla RecordatoriosCustomFunctions end
//-------------------------------

session_start();

//===============================
// Save Page and File Name available into variables
//-------------------------------
$filename = "Listar Recordatorios.php";
$template_filename = "Listar Recordatorios.html";
//===============================



//===============================
// Inicio RecordatoriosPageSecurity begin
//check_security(3);
// Inicio RecordatoriosPageSecurity end
//===============================

//===============================
// Inicio RecordatoriosOpen Event begin
// Inicio RecordatoriosOpen Event end
//===============================

//===============================
// Inicio RecordatoriosOpenAnyPage Event start
// Inicio RecordatoriosOpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

// Inicio RecordatoriosShow begin

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
//-------------------------------
$tpl->set_var("FileName", $filename);



//-------------------------------
// Step through each form
//-------------------------------
recs_show();

//-------------------------------
// Process page templates
//-------------------------------
//-------------------------------
// Output the page to the browser
//-------------------------------
$tpl->pparse("main", false);
// Inicio RecordatoriosShow end

//===============================
// Inicio RecordatoriosClose Event begin
// Inicio RecordatoriosClose Event end
//===============================
//********************************************************************************


//===============================
// Display Grid Form
//-------------------------------
function recs_show()
{
//-------------------------------
// Initialize variables  
//-------------------------------
  
  global $tpl;
  global $db;
  global $srecsErr;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $sFormTitle = "Recordatorios";
  $HasParam = false;
  $iRecordsPerPage = 20;
  $iCounter = 0;
  $iPage = 0;
  $bEof = false;
  $iSort = "";
  $iSorted = "";
  $sDirection = "";
  $sSortParams = "";
  $sActionFileName = "Editar Recordatorios.php";

  $SQL="SELECT nombre FROM clientes where id_cliente=" . get_param("id_cliente_nav");
  $db->query($SQL);  
  $db->next_record();
  $sNombreCliente=$db->f("nombre");

  $tpl->set_var("TransitParams", "id_cliente_nav=" . get_param("id_cliente_nav"));
  $tpl->set_var("FormParams", "id_cliente_nav=" . get_param("id_cliente_nav"));

//-------------------------------
// Build WHERE statement
//-------------------------------
  $pid_cliente = get_param("id_cliente_nav");

  if(strlen($pid_cliente))
  {
    $HasParam = true;
    $sWhere = $sWhere . "id_cliente=" . tosql($pid_cliente,"Number");
  }

  if(strlen($sWhere))
     $sWhere= " WHERE " . $sWhere;

//-------------------------------
// Build ORDER BY statement
//-------------------------------
  $sOrder = " order by r.fecha_aplicacion ASC";
  $iSort = get_param("Formrecs_Sorting");
  $iSorted = get_param("Formrecs_Sorted");
  if(!$iSort)
  {
    $tpl->set_var("Form_Sorting", "");
    $sOrder = " order by r.estatus ASC,r.fecha_aplicacion ASC";
  }
  else
  {
    if($iSort == $iSorted)
    {
      $tpl->set_var("Form_Sorting", "");
      $sDirection = " DESC";
      $sSortParams = "Formrecs_Sorting=" . $iSort . "&Formrecs_Sorted=" . $iSort . "&";
    }
    else
    {
      $tpl->set_var("Form_Sorting", $iSort);
      $sDirection = " ASC";
      $sSortParams = "Formrecs_Sorting=" . $iSort . "&Formrecs_Sorted=" . "&";
    }
    switch($iSort){
    	case 1: 
		$sOrder = " order by r.estatus ASC,r.fecha_aplicacion" . $sDirection;
		break;
	case 2: 
		$sOrder = " order by r.estatus ASC,r.notas" . $sDirection;
		break;
        default:
		$sOrder = " order by r.estatus ASC,r.fecha_aplicacion ASC";
		break;
    }
  }

//-------------------------------
// Build base SQL statement
//-------------------------------
  $sSQL = "select r.id_recordatorio as r_id_recordatorio, " . 
   "r.estatus as r_estatus, " .
   "r.fecha_aplicacion as r_fecha_aplicacion, " . 
    "r.notas as r_notas " . 
    " from recordatorios r ";
//-------------------------------

//-------------------------------
// recs Open Event begin
// recs Open Event end
//-------------------------------

//-------------------------------
// Assemble full SQL statement
//-------------------------------
  $sSQL .= $sWhere . $sOrder;
//  echo($sSQL);
//-------------------------------

  $tpl->set_var("FormTitle", $sFormTitle);
  $tpl->set_var("NombreCliente",$sNombreCliente);
  $tpl->set_var("id_cliente_nav",get_param("id_cliente_nav"));

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
// Process empty recordset
//-------------------------------
  if(!$next_record)
  {
    $tpl->set_var("DListrecs", "");
    $tpl->parse("recsNoRecords", false);
    $tpl->set_var("recsNavigator", "");
    $tpl->parse("Formrecs", false);
    return;
  }
  
//-------------------------------
//-------------------------------
// Initialize page counter and records per page
//-------------------------------
  $iRecordsPerPage = 20;
  $iCounter = 0;
//-------------------------------

//-------------------------------
// Process page scroller
//-------------------------------
  $iPage = get_param("Formrecs_Page");
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
    $fldrec_id_URLLink = "Editar Recordatorio.php";
    $fldrec_id_rec_id = $db->f("r_id_recordatorio");
    $fldrec_id = $db->f("r_id_recordatorio");
    $fldrec_fecha_aplicacion = avoid_nulls($db->f("r_fecha_aplicacion"));    
    $fldrec_notas = avoid_nulls($db->f("r_notas"));    
    $fldrec_estatus = $db->f("r_estatus");
    $fldrec_edit= "Editar";


    $next_record = $db->next_record();
    
//-------------------------------
// recs Show begin
//-------------------------------

//-------------------------------
// recs Show Event begin
// recs Show Event end
//-------------------------------

//-------------------------------
// Replace Template fields with database values
//-------------------------------
    
      $tpl->set_var("rec_id", tohtml($fldrec_id));
      $tpl->set_var("rec_edit", tohtml($fldrec_edit));
      $tpl->set_var("rec_id_URLLink", $fldrec_id_URLLink);
      $tpl->set_var("Prmrec_id_rec_id", urlencode($fldrec_id_rec_id));
      $tpl->set_var("fecha_aplicacion", tohtml($fldrec_fecha_aplicacion));
      $tpl->set_var("notas", tohtml($fldrec_notas));
      $tpl->set_var("id_recordatorio",$fldrec_id);
//-------------------------------------------
//Dynamically control BGCOLOR and FONT-WEIGHT
//-------------------------------------------

      $curdate = mktime(0,0,0,date("m"),date("d"),date("Y"));

      if(($fldrec_estatus == 0) && (is_date($fldrec_fecha_aplicacion)) &&(strtotime($fldrec_fecha_aplicacion) < $curdate))
        $fldcolor="red";
      else
      { 
	if(($fldrec_estatus == 0) && (is_date($fldrec_fecha_aplicacion)) &&(strtotime($fldrec_fecha_aplicacion) == $curdate))
	  $fldcolor="cyan";
        else
          $fldcolor="#EEEEEE";
      }
      $tpl->set_var("color",$fldcolor);

      if($fldrec_estatus==0)
        $fldweight="bold";
      else
        $fldweight="normal";
      $tpl->set_var("weight",$fldweight);

      $tpl->parse("DListrecs", true);
//-------------------------------
// recs Show end
//-------------------------------

//-------------------------------
// Move to the next record and increase record counter
//-------------------------------
    
    $iCounter++;
  }

  // recs Navigation begin
  $bEof = $next_record;
  // Parse Navigator
  if(!$bEof && $iPage == 1)
    $tpl->set_var("recsNavigator", "");
  else 
  {
    if(!$bEof)
      $tpl->set_var("recsNavigatorLastPage", "_");
    else
      $tpl->set_var("NextPage", ($iPage + 1));
    if($iPage == 1)
      $tpl->set_var("recsNavigatorFirstPage", "_");
    else
      $tpl->set_var("PrevPage", ($iPage - 1));
    $tpl->set_var("recsCurrentPage", $iPage);
    $tpl->parse("recsNavigator", false);
  }

//-------------------------------
// recs Navigation end
//-------------------------------

//-------------------------------
// Finish form processing
//-------------------------------
  $tpl->set_var( "recsNoRecords", "");

  $tpl->parse( "Formrecs", false);
//-------------------------------
// recs Close Event begin
// recs Close Event end
//-------------------------------
}
//===============================

?>