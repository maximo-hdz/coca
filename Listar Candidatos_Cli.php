<?php
/*********************************************************************************
 *       Filename: Listar Candidatos.php
 *       Proyecto Coca Consultores
 *       Adrian Bisiacchi 
 *       PHP 4.0 & Tcandlates Escrito el 10/04/2002
 *********************************************************************************/

//-------------------------------
// Tabla CandidatosCustomIncludes begin

include ("./common.php");

// Tabla CandidatosCustomIncludes end
//-------------------------------


//-------------------------------
// Tabla CandidatosCustomFunctions begin

function avoid_nulls($string)
{
  if(!strlen($string))
    return "????";
  else
    return $string;
}

// Tabla CandidatosCustomFunctions end
//-------------------------------

session_start();

//===============================
// Save Page and cand Name available into variables
//-------------------------------
$filename = "Listar Candidatos_Cli.php";
$template_filename = "Listar Candidatos_Cli.html";
//===============================



//===============================
// Listar Candidatos_CliPageSecurity begin
check_security(2);
// Listar Candidatos_CliPageSecurity end
//===============================

//===============================
// Listar Candidatos_CliOpen Event begin
// Listar Candidatos_CliOpen Event end
//===============================

//===============================
// Listar Candidatos_CliOpenAnyPage Event start
// Listar Candidatos_CliOpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

// Listar Candidatos_CliShow begin

//===============================
// Display page
//-------------------------------
// Load HTML tcandlate for this page
//-------------------------------
$tpl = new Template($app_path);
$tpl->load_file($template_filename, "main");
//-------------------------------
// Load HTML tcandlate of Header and Footer
//-------------------------------
//-------------------------------
$tpl->set_var("FileName", $filename);



//-------------------------------
// Step through each form
//-------------------------------
cands_show();

//-------------------------------
// Process page tcandlates
//-------------------------------
//-------------------------------
// Output the page to the browser
//-------------------------------
$tpl->pparse("main", false);
// Listar Candidatos_CliShow end

//===============================
// Listar Candidatos_CliClose Event begin
// Listar Candidatos_CliClose Event end
//===============================
//********************************************************************************


//===============================
// Display Grid Form
//-------------------------------
function cands_show()
{
//-------------------------------
// Initialize variables  
//-------------------------------
  
  global $tpl;
  global $db;
  global $scandsErr;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $sFormTitle = "Candidatos";
  $HasParam = false;
  $iRecordsPerPage = 20;
  $iCounter = 0;
  $iPage = 0;
  $bEof = false;
  $iSort = "";
  $iSorted = "";
  $sDirection = "";
  $sSortParams = "";
  $sActionFileName = "Editar Candidatos.php";
  $fldid_cliente=get_param("id_cliente_nav");


  $tpl->set_var("TransitParams", "id_cliente_nav=" . get_param("id_cliente_nav") . "&" );
  $tpl->set_var("FormParams", "id_cliente_nav=" . get_param("id_cliente_nav") . "&");

  if(strlen(get_param("Err")))
      $scandsErr=strip(get_param("Err"));
  else 
      $scandsErr="";

//-------------------------------
// Build ORDER BY statement
//-------------------------------
  $iSort = get_param("Formcands_Sorting");
  $iSorted = get_param("Formcands_Sorted");
    if($iSort == $iSorted)
    {
      $tpl->set_var("Form_Sorting", "");
      $sDirection = " DESC";
      $sSortParams = "Formcands_Sorting=" . $iSort . "&Formcands_Sorted=" . $iSort . "&";
    }
    else
    {
      $tpl->set_var("Form_Sorting", $iSort);
      $sDirection = " ASC";
      $sSortParams = "Formcands_Sorting=" . $iSort . "&Formcands_Sorted=" . "&";
    }
    switch($iSort){
    	case 1: 
		$sOrder = " order by c.nombres" . $sDirection;
		break;
    	case 2: 
		$sOrder = " order by c.apaterno" . $sDirection;
		break;
    	case 3: 
		$sOrder = " order by c.amaterno" . $sDirection;
		break;
    	case 4: 
		$sOrder = " order by c.nivel_estudios" . $sDirection;
		break;
    	case 5: 
		$sOrder = " order by c.carrera" . $sDirection;
		break;
    	case 6: 
		$sOrder = " order by c.sueldo_min" . $sDirection;
		break;
    	case 7: 
		$sOrder = " order by c.recomendado" . $sDirection;
		break;
    }

//-------------------------------
// Build base SQL statement
//-------------------------------
  $sSQL = "select c.id_Candidato as c_id_Candidato, " . 
   "c.nombres as c_nombre " . " ," .
   "c.apaterno as c_apaterno " . " ," .   
   "c.amaterno as c_amaterno " . "," .
   "cat1.descript as c_nivel_estudios " . "," .
   "car.nombre as c_carrera " . "," .
   "c.sueldo_min as c_sueldo_min " . "," .
   "c.recomendado as c_recomendado " .
   " from Candidatos c left outer join catalog cat1 on cat1.par_id=5 and cat1.value=c.nivel_estudios " .
   " left outer join carreras car on car.id_carrera=c.carrera ";
//-------------------------------

$sWhere=" WHERE ESTATUS=1 and id_cliente=" . tosql($fldid_cliente,"Number");

//-------------------------------
// cands Open Event begin
// cands Open Event end
//-------------------------------

//-------------------------------
// Assemble full SQL statement
//-------------------------------
  $sSQL .= $sWhere . $sOrder;
//echo($sSQL);
//-------------------------------

  $tpl->set_var("FormTitle", $sFormTitle);
  $tpl->set_var("id_cliente_nav",get_param("id_cliente_nav"));

  if(strlen($scandsErr)){
      $tpl->set_var("errMsg",$scandsErr);
      $tpl->parse(candsErr,false);}
  else
      $tpl->set_var("candsErr","");


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
// Process candty recordset
//-------------------------------
  if(!$next_record)
  {
    $tpl->set_var("DListcands", "");
    $tpl->parse("candsNoRecords", false);
    $tpl->set_var("candsNavigator", "");
    $tpl->parse("Formcands", false);
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
  $iPage = get_param("Formcands_Page");
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
    $fldcand_id_URLLink = "Editar Candidatos.php";
    $fldcand_id_cand_id = $db->f("c_id_Candidato");
    $fldcand_id = $db->f("c_id_Candidato");
    $fldcand_edit = "Editar";    
    $fldcand_nombre = avoid_nulls($db->f("c_nombre"));    
    $fldcand_apaterno = avoid_nulls($db->f("c_apaterno"));    
    $fldcand_amaterno = avoid_nulls($db->f("c_amaterno"));    
    $fldcand_nivel_estudios = avoid_nulls($db->f("c_nivel_estudios"));    
    $fldcand_carrera = avoid_nulls($db->f("c_carrera"));    
    $fldcand_sueldo_min = avoid_nulls($db->f("c_sueldo_min"));    
    $fldcand_recomendado = avoid_nulls($db->f("c_recomendado"));    

    $next_record = $db->next_record();
    
//-------------------------------
// recs Show begin
//-------------------------------

//-------------------------------
// cands Show Event begin
// cands Show Event end
//-------------------------------

//-------------------------------
// Replace Tcandlate fields with database values
//-------------------------------
    
      $tpl->set_var("cand_id", tohtml($fldcand_id));
      $tpl->set_var("cand_edit", tohtml($fldcand_edit));
      $tpl->set_var("cand_id_URLLink", $fldcand_id_URLLink);
      $tpl->set_var("Prmcand_id_cand_id", urlencode($fldcand_id_cand_id));
      $tpl->set_var("cand_name", tohtml($fldcand_nombre));
      $tpl->set_var("cand_apaterno", tohtml($fldcand_apaterno));
      $tpl->set_var("cand_amaterno", tohtml($fldcand_amaterno));
      $tpl->set_var("cand_nivel_estudios", tohtml($fldcand_nivel_estudios));
      $tpl->set_var("cand_carrera", tohtml($fldcand_carrera));
      $tpl->set_var("cand_sueldo_min", tohtml($fldcand_sueldo_min));
      if ($fldcand_recomendado==0)
	      $tpl->set_var("cand_recomendado", tohtml("No"));
      if ($fldcand_recomendado==1)
	      $tpl->set_var("cand_recomendado", tohtml("Si"));


      $tpl->set_var("id_Candidato",$fldcand_id);
//-------------------------------------------
//Dynamically control BGCOLOR and FONT-WEIGHT
//-------------------------------------------

      $tpl->set_var("color",$fldcolor);
 
      if(!security_info(3))
         $tpl->set_var("Security_2_2","");
      else
         $tpl->parse("Security_2_2",false);

      $tpl->parse("DListcands", true);
//-------------------------------
// cands Show end
//-------------------------------

//-------------------------------
// Move to the next candord and increase record counter
//-------------------------------
    
    $iCounter++;
  }

  // cands Navigation begin
  $bEof = $next_record;
  // Parse Navigator
  if(!$bEof && $iPage == 1)
    $tpl->set_var("candsNavigator", "");
  else 
  {
    if(!$bEof)
      $tpl->set_var("candsNavigatorLastPage", "_");
    else
      $tpl->set_var("NextPage", ($iPage + 1));
    if($iPage == 1)
      $tpl->set_var("candsNavigatorFirstPage", "_");
    else
      $tpl->set_var("PrevPage", ($iPage - 1));
    $tpl->set_var("candsCurrentPage", $iPage);
    $tpl->parse("candsNavigator", false);
  }

//-------------------------------
// cands Navigation end
//-------------------------------

//-------------------------------
// Finish form processing
//-------------------------------
  $tpl->set_var("id_cliente_nav",get_param("id_cliente_nav"));
  $tpl->set_var( "candsNoRecords", "");

      if(!security_info(3))
         $tpl->set_var("Security_2_1","");
      else
         $tpl->parse("Security_2_1",false);


  $tpl->parse( "Formcands", false);
//-------------------------------
// cands Close Event begin
// cands Close Event end
//-------------------------------
}
//===============================

?>