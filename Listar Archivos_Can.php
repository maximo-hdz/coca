<?php
/*********************************************************************************
 *       Filename: Listar Archivos.php
 *       Proyecto Coca Consultores
 *       Adrian Bisiacchi 
 *       PHP 4.0 & Templates Escrito el 10/04/2002
 *********************************************************************************/

//-------------------------------
// Tabla ArchivosCustomIncludes begin

include ("./common.php");

// Tabla ArchivosCustomIncludes end
//-------------------------------


//-------------------------------
// Tabla ArchivosCustomFunctions begin

function avoid_nulls($string)
{
  if(!strlen($string))
    return "????";
  else
    return $string;
}

// Tabla ArchivosCustomFunctions end
//-------------------------------

session_start();

//===============================
// Save Page and File Name available into variables
//-------------------------------
$filename = "Listar Archivos_Can.php";
$template_filename = "Listar Archivos_Can.html";
//===============================



//===============================
// Inicio ArchivosPageSecurity begin
check_security(2);
// Inicio ArchivosPageSecurity end
//===============================

//===============================
// Inicio ArchivosOpen Event begin
// Inicio ArchivosOpen Event end
//===============================

//===============================
// Inicio ArchivosOpenAnyPage Event start
// Inicio ArchivosOpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

// Inicio ArchivosShow begin

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
files_show();

//-------------------------------
// Process page templates
//-------------------------------
//-------------------------------
// Output the page to the browser
//-------------------------------
$tpl->pparse("main", false);
// Inicio ArchivosShow end

//===============================
// Inicio ArchivosClose Event begin
// Inicio ArchivosClose Event end
//===============================
//********************************************************************************


//===============================
// Display Grid Form
//-------------------------------
function files_show()
{
//-------------------------------
// Initialize variables  
//-------------------------------
  
  global $tpl;
  global $db;
  global $sfilesErr;
  $sWhere = "";
  $sOrder = "";
  $sSQL = "";
  $sFormTitle = "Archivos";
  $HasParam = false;
  $iRecordsPerPage = 20;
  $iCounter = 0;
  $iPage = 0;
  $bEof = false;
  $iSort = "";
  $iSorted = "";
  $sDirection = "";
  $sSortParams = "";
  $sActionFileName = "Editar Archivos.php";

  $SQL="SELECT * FROM candidatos where id_candidato=" . get_param("id_candidato");
  $db->query($SQL);  
  $db->next_record();
  $sNombrecandidato=$db->f("nombres") . " " . $db->f("apaterno") . " " . $db->f("amaterno");

  $tpl->set_var("TransitParams", "id_candidato=" . get_param("id_candidato") . "&" );
  $tpl->set_var("FormParams", "id_candidato=" . get_param("id_candidato") . "&");

  if(strlen(get_param("Err")))
      $sfilesErr=strip(get_param("Err"));
  else 
      $sfilesErr="";

//-------------------------------
// Build WHERE statement
//-------------------------------
  $pid_candidato = get_param("id_candidato");

  if(strlen($pid_candidato))
  {
    $HasParam = true;
    $sWhere = $sWhere . "id_candidato=" . tosql($pid_candidato,"Number");
  }

  if(strlen($sWhere))
     $sWhere= " WHERE " . $sWhere;

//-------------------------------
// Build ORDER BY statement
//-------------------------------
  $iSort = get_param("Formfiles_Sorting");
  $iSorted = get_param("Formfiles_Sorted");
    if($iSort == $iSorted)
    {
      $tpl->set_var("Form_Sorting", "");
      $sDirection = " DESC";
      $sSortParams = "Formfiles_Sorting=" . $iSort . "&Formfiles_Sorted=" . $iSort . "&";
    }
    else
    {
      $tpl->set_var("Form_Sorting", $iSort);
      $sDirection = " ASC";
      $sSortParams = "Formfiles_Sorting=" . $iSort . "&Formfiles_Sorted=" . "&";
    }
    switch($iSort){
    	case 1: 
		$sOrder = " order by f.nombre" . $sDirection;
		break;
    }

//-------------------------------
// Build base SQL statement
//-------------------------------
  $sSQL = "select f.id_Archivo as f_id_Archivo, " . 
   "f.nombre as f_nombre " .
    " from Archivos f ";
//-------------------------------

//-------------------------------
// files Open Event begin
// files Open Event end
//-------------------------------

//-------------------------------
// Assemble full SQL statement
//-------------------------------
  $sSQL .= $sWhere . $sOrder;
//  echo($sSQL);
//-------------------------------

  $tpl->set_var("FormTitle", $sFormTitle);
  $tpl->set_var("NombreCandidato",$sNombrecandidato);
  $tpl->set_var("id_candidato_nav",get_param("id_candidato"));

  if(strlen($sfilesErr)){
      $tpl->set_var("errMsg",$sfilesErr);
      $tpl->parse(FilesErr,false);}
  else
      $tpl->set_var("FilesErr","");


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
    $tpl->set_var("DListfiles", "");
    $tpl->parse("filesNoRecords", false);
    $tpl->set_var("filesNavigator", "");
    $tpl->parse("Formfiles", false);
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
  $iPage = get_param("Formfiles_Page");
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
    $fldfile_id_URLLink = "Download_Can.php";
    $fldfile_id_file_id = $db->f("f_id_Archivo");
    $fldfile_id = $db->f("f_id_Archivo");
    $fldfile_dnld = "Descargar";    
    $fldfile_nombre = avoid_nulls($db->f("f_nombre"));    
    $fldfile_edit= "Editar";


    $next_record = $db->next_record();
    
//-------------------------------
// recs Show begin
//-------------------------------

//-------------------------------
// files Show Event begin
// files Show Event end
//-------------------------------

//-------------------------------
// Replace Template fields with database values
//-------------------------------
    
      $tpl->set_var("file_id", tohtml($fldfile_id));
      $tpl->set_var("file_dnld", tohtml($fldfile_dnld));
      $tpl->set_var("file_id_URLLink", $fldfile_id_URLLink);
      $tpl->set_var("Prmfile_id_file_id", urlencode($fldfile_id_file_id));
      $tpl->set_var("file_name", tohtml($fldfile_nombre));
      $tpl->set_var("id_Archivo",$fldfile_id);
//-------------------------------------------
//Dynamically control BGCOLOR and FONT-WEIGHT
//-------------------------------------------

        $fldcolor="#EEEEEE";
      $tpl->set_var("color",$fldcolor);

      $tpl->parse("DListfiles", true);
//-------------------------------
// files Show end
//-------------------------------

//-------------------------------
// Move to the next fileord and increase record counter
//-------------------------------
    
    $iCounter++;
  }

  // files Navigation begin
  $bEof = $next_record;
  // Parse Navigator
  if(!$bEof && $iPage == 1)
    $tpl->set_var("filesNavigator", "");
  else 
  {
    if(!$bEof)
      $tpl->set_var("filesNavigatorLastPage", "_");
    else
      $tpl->set_var("NextPage", ($iPage + 1));
    if($iPage == 1)
      $tpl->set_var("filesNavigatorFirstPage", "_");
    else
      $tpl->set_var("PrevPage", ($iPage - 1));
    $tpl->set_var("filesCurrentPage", $iPage);
    $tpl->parse("filesNavigator", false);
  }

//-------------------------------
// files Navigation end
//-------------------------------

//-------------------------------
// Finish form processing
//-------------------------------
  $tpl->set_var( "filesNoRecords", "");
  $tpl->set_var("id_candidato_nav",tohtml(get_param("id_candidato")));
  $tpl->set_var("id_candidato",tohtml(get_param("id_candidato")));

  $tpl->parse( "Formfiles", false);
//-------------------------------
// files Close Event begin
// files Close Event end
//-------------------------------
}
//===============================

?>