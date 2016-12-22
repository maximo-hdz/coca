<?php
/*********************************************************************************
 *       empname: Listar Fuentes.php
 *       Proyecto Coca Consultores
 *       Adrian Bisiacchi 
 *       PHP 4.0 & Templates Escrito el 10/04/2002
 *********************************************************************************/

//-------------------------------
// Tabla FuentesCustomIncludes begin

include ("./common.php");

// Tabla FuentesCustomIncludes end
//-------------------------------


//-------------------------------
// Tabla FuentesCustomFunctions begin

function avoid_nulls($string)
{
  if(!strlen($string))
    return "????";
  else
    return $string;
}

// Tabla FuentesCustomFunctions end
//-------------------------------

session_start();

//===============================
// Save Page and emp Name available into variables
//-------------------------------
$filename = "Inicio Fuentes.php";
$template_filename = "Inicio Fuentes.html";
//===============================



//===============================
// Inicio FuentesPageSecurity begin
check_security(2);
// Inicio FuentesPageSecurity end
//===============================

//===============================
// Inicio FuentesOpen Event begin
// Inicio FuentesOpen Event end
//===============================

//===============================
// Inicio FuentesOpenAnyPage Event start
// Inicio FuentesOpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

// Inicio FuentesShow begin

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
emps_show();

//-------------------------------
// Process page templates
//-------------------------------
//-------------------------------
// Output the page to the browser
//-------------------------------
$tpl->pparse("main", false);
// Inicio FuentesShow end

//===============================
// Inicio FuentesClose Event begin
// Inicio FuentesClose Event end
//===============================
//********************************************************************************


//===============================
// Display Grid Form
//-------------------------------
function emps_show()
{
//-------------------------------
// Initialize variables  
//-------------------------------
  
  global $tpl;
  global $db;
  global $sempsErr;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $sFormTitle = "Fuentes";
  $HasParam = false;
  $iRecordsPerPage = 20;
  $iCounter = 0;
  $iPage = 0;
  $bEof = false;
  $iSort = "";
  $iSorted = "";
  $sDirection = "";
  $sSortParams = "";
  $sActionFileName = "Editar Fuentes.php";


  $tpl->set_var("TransitParams", "id_cliente_nav=" . get_param("id_cliente_nav") . "&" );
  $tpl->set_var("FormParams", "id_cliente_nav=" . get_param("id_cliente_nav") . "&");

  if(strlen(get_param("Err")))
      $sempsErr=strip(get_param("Err"));
  else 
      $sempsErr="";

//-------------------------------
// Build ORDER BY statement
//-------------------------------
  $iSort = get_param("Formemps_Sorting");
  $iSorted = get_param("Formemps_Sorted");
    if($iSort == $iSorted)
    {
      $tpl->set_var("Form_Sorting", "");
      $sDirection = " DESC";
      $sSortParams = "Formemps_Sorting=" . $iSort . "&Formemps_Sorted=" . $iSort . "&";
    }
    else
    {
      $tpl->set_var("Form_Sorting", $iSort);
      $sDirection = " ASC";
      $sSortParams = "Formemps_Sorting=" . $iSort . "&Formemps_Sorted=" . "&";
    }
    switch($iSort){
    	case 1: 
		$sOrder = " order by e.nombre" . $sDirection;
		break;
//    	case 2: 
//		$sOrder = " order by e.apaterno" . $sDirection;
//		break;
//    	case 3: 
//		$sOrder = " order by e.amaterno" . $sDirection;
//		break;
    }

//-------------------------------
// Build base SQL statement
//-------------------------------
  $sSQL = "select e.id_Fuente as e_id_Fuente, " . 
   "e.nombre as e_nombre " . 
   " from Fuentes e";
//-------------------------------


//-------------------------------
// emps Open Event begin
// emps Open Event end
//-------------------------------

//-------------------------------
// Assemble full SQL statement
//-------------------------------
  $sSQL .=  $sOrder;
//  echo($sSQL);
//-------------------------------

  $tpl->set_var("FormTitle", $sFormTitle);
  $tpl->set_var("id_cliente_nav",get_param("id_cliente_nav"));

  if(strlen($sempsErr)){
      $tpl->set_var("errMsg",$sempsErr);
      $tpl->parse(empsErr,false);}
  else
      $tpl->set_var("empsErr","");


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
    $tpl->set_var("DListemps", "");
    $tpl->parse("empsNoRecords", false);
    $tpl->set_var("empsNavigator", "");
    $tpl->parse("Formemps", false);
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
  $iPage = get_param("Formemps_Page");
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
    $fldemp_id_URLLink = "Editar Fuentes.php";
    $fldemp_id_emp_id = $db->f("e_id_Fuente");
    $fldemp_id = $db->f("e_id_Fuente");
    $fldemp_edit = "Editar";    
    $fldemp_nombre = avoid_nulls($db->f("e_nombre"));    
//    $fldemp_apaterno = avoid_nulls($db->f("e_apaterno"));    
//    $fldemp_amaterno = avoid_nulls($db->f("e_amaterno"));    

    $next_record = $db->next_record();
    
//-------------------------------
// recs Show begin
//-------------------------------

//-------------------------------
// emps Show Event begin
// emps Show Event end
//-------------------------------

//-------------------------------
// Replace Template fields with database values
//-------------------------------
    
      $tpl->set_var("emp_id", tohtml($fldemp_id));
      $tpl->set_var("emp_edit", tohtml($fldemp_edit));
      $tpl->set_var("emp_id_URLLink", $fldemp_id_URLLink);
      $tpl->set_var("Prmemp_id_emp_id", urlencode($fldemp_id_emp_id));
      $tpl->set_var("emp_name", tohtml($fldemp_nombre));
//      $tpl->set_var("emp_apaterno", tohtml($fldemp_apaterno));
//      $tpl->set_var("emp_amaterno", tohtml($fldemp_amaterno));
      $tpl->set_var("id_Fuente",$fldemp_id);
//-------------------------------------------
//Dynamically control BGCOLOR and FONT-WEIGHT
//-------------------------------------------

      $tpl->set_var("color",$fldcolor);

      $tpl->parse("DListemps", true);
//-------------------------------
// emps Show end
//-------------------------------

//-------------------------------
// Move to the next empord and increase record counter
//-------------------------------
    
    $iCounter++;
  }

  // emps Navigation begin
  $bEof = $next_record;
  // Parse Navigator
  if(!$bEof && $iPage == 1)
    $tpl->set_var("empsNavigator", "");
  else 
  {
    if(!$bEof)
      $tpl->set_var("empsNavigatorLastPage", "_");
    else
      $tpl->set_var("NextPage", ($iPage + 1));
    if($iPage == 1)
      $tpl->set_var("empsNavigatorFirstPage", "_");
    else
      $tpl->set_var("PrevPage", ($iPage - 1));
    $tpl->set_var("empsCurrentPage", $iPage);
    $tpl->parse("empsNavigator", false);
  }

//-------------------------------
// emps Navigation end
//-------------------------------

//-------------------------------
// Finish form processing
//-------------------------------
  $tpl->set_var( "empsNoRecords", "");

  $tpl->parse( "Formemps", false);
//-------------------------------
// emps Close Event begin
// emps Close Event end
//-------------------------------
}
//===============================

?>