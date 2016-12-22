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
$filename = "Inicio Candidatos.php";
$template_filename = "Inicio Candidatos.html";
//===============================

//-------------------------------
//    Error Vars
//-------------------------------
$sBusquedaErr="";

//===============================
// Inicio CandidatosPageSecurity begin
check_security(1);
// Inicio CandidatosPageSecurity end
//===============================

//===============================
// Inicio CandidatosOpen Event begin
// Inicio CandidatosOpen Event end
//===============================

//===============================
// Inicio CandidatosOpenAnyPage Event start
// Inicio CandidatosOpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

// Inicio CandidatosShow begin

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

switch($sForm)
{
  case "Navegacion":
    nav_action($sAction);
   break;
}


//-------------------------------
// Step through each form
//-------------------------------
nav_show();cands_show();Busqueda_show();

//-------------------------------
// Process page tcandlates
//-------------------------------
//-------------------------------
// Output the page to the browser
//-------------------------------

if(!security_info(2)){
 $tpl->set_var("Security_2_1", "");
}
else
{
 $tpl->parse("Security_2_1", false);
}

$tpl->pparse("main", false);
// Inicio CandidatosShow end

//===============================
// Inicio CandidatosClose Event begin
// Inicio CandidatosClose Event end
//===============================
//********************************************************************************


function nav_action($sAction)
{
  global $db;
  global $tpl;
  check_security(2);
  switch($sAction)
  {
     case "asignar":
       $fldid_candidato=get_param("id_candidato_nav");
       $fldid_cliente=get_param("clientes");
       if($fldid_cliente != -1)
       {
         $iSQL="UPDATE CANDIDATOS SET ESTATUS=1, ID_CLIENTE=" . tosql($fldid_cliente, "Number") .
               " WHERE ID_CANDIDATO=" . tosql($fldid_candidato,"Number");  
         $db->query($iSQL);
       }
       return;
     break;

     case "recomendar":
       $fldid_candidato=get_param("id_candidato_nav");
       $fldrecomendado=get_param("recomendado");
       if(strlen($fldrecomendado))
       {
         $iSQL="UPDATE CANDIDATOS SET RECOMENDADO=1, recomendado_por=" . tosql($fldrecomendado, "TEXT") .
               " WHERE ID_CANDIDATO=" . tosql($fldid_candidato,"Number");  
         $db->query($iSQL);
       }
       return;
     break;
     case "archivos":
       $fldid_candidato=get_param("id_candidato_nav");
       header("Location: Listar Archivos_Can.php?id_candidato=" . tourl($fldid_candidato)); 
       return;
     break;
     case "prorroga":
       $fldid_candidato=get_param("id_candidato_nav");
         $iSQL="UPDATE CANDIDATOS SET fecha_alta=Current_Date " . 
               " WHERE ID_CANDIDATO=" . tosql($fldid_candidato,"Number");  
         $db->query($iSQL);
       return;
     break;  
  }
}


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
  global $sBusquedaErr;
  $sWhere = "";
  $sOrder = "";
  $sJoin = "";
  $sGroup = "";
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



  $tpl->set_var("TransitParams", "id_candidato=" . tourl(get_param("id_candidato")) . "&nombre=" . tourl(get_param("nombre")) . "&apaterno=" . tourl(get_param("apaterno")) . "&amaterno=" . tourl(get_param("apaterno")) . "&amaterno=" . tourl(get_param("amaterno")) . "&sueldo_low=" . tourl(get_param("sueldo_low")) . "&sueldo_high=" . tourl(get_param("sueldo_high")) . "&antiguedad=" . tourl(get_param("antiguedad")) . "&clave=" . tourl(get_param("clave")));
  $sFormParams="id_candidato=" . tourl(get_param("id_candidato")) . "&nombre=" . tourl(get_param("nombre")) . "&apaterno=" . tourl(get_param("apaterno")) . "&amaterno=" . tourl(get_param("apaterno")) . "&amaterno=" . tourl(get_param("amaterno")) . "&sueldo_low=" . tourl(get_param("sueldo_low")) . "&sueldo_high=" . tourl(get_param("sueldo_high")) . "&antiguedad=" . tourl(get_param("antiguedad")) . "&clave=" . tourl(get_param("clave"));

  if((get_param("carrera") != -1)&&strlen(get_param("carrera")))
     $sFormParams=$sFormParams . "&carrera=" . tourl(get_param("carrera"));

  if((get_param("sexo") != -1)&&strlen(get_param("sexo")))
     $sFormParams=$sFormParams . "&sexo=" . tourl(get_param("sexo"));

  if((get_param("nivel_estudios") != -1)&&strlen(get_param("nivel_estudios")))
     $sFormParams=$sFormParams . "&nivel_estudios=" . tourl(get_param("nivel_estudios"));

  $sSortParams="Formcands_Sorting=" . tourl(get_param("Formcands_Sorting")) . "&Formcands_Sorted=" . tourl(get_param("Formcands_Sorted")) . "&";
  $tpl->set_var("FormParams", $sFormParams."&");

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


$sWhere=" ESTATUS=0";

/**********************************************************/
  $pnombre = get_param("nombre");

  if(strlen($pnombre))
  {
    if($sWhere != "") 
      $sWhere .= " and ";
    $HasParam = true;
    $sWhere = $sWhere . "c.nombres like" . tosql("%" . $pnombre . "%","Text");
  }

  $papaterno = get_param("apaterno");

  if(strlen($papaterno))
  {
    if($sWhere != "") 
      $sWhere .= " and ";
    $HasParam = true;
    $sWhere = $sWhere . "c.apaterno like" . tosql("%" . $papaterno . "%","Text");
  }

  $pamaterno = get_param("amaterno");

  if(strlen($pamaterno))
  {
    if($sWhere != "") 
      $sWhere .= " and ";
    $HasParam = true;
    $sWhere = $sWhere . "c.amaterno like" . tosql("%" . $pamaterno . "%","Text");
  }

  $pcarrera = get_param("carrera");
  
  if(($pcarrera != -1) && (strlen($pcarrera)))
  {
    if($sWhere != "") 
      $sWhere .= " and ";
    $HasParam = true;
    $sWhere = $sWhere . "c.carrera =" . tosql($pcarrera,"Number");
  }

  $psexo = get_param("sexo");
  
  if(($psexo != -1) && (strlen($psexo)))
  {
    if($sWhere != "") 
      $sWhere .= " and ";
    $HasParam = true;
    $sWhere = $sWhere . "c.sexo =" . tosql($psexo,"Number");
  }

  $pnivel_estudios = get_param("nivel_estudios");
  
  if(($pnivel_estudios != -1) && (strlen($pnivel_estudios)))
  {
    if($sWhere != "") 
      $sWhere .= " and ";
    $HasParam = true;
    $sWhere = $sWhere . "c.nivel_estudios =" . tosql($pnivel_estudios,"Number");
  }

  $psueldo_high = get_param("sueldo_high");
  $psueldo_low = get_param("sueldo_low");
 
  if((strlen($psueldo_high)&&!is_numeric($psueldo_high))||(strlen($psueldo_low)&&!is_numeric($psueldo_low)))
  {
    $sBusquedaErr.="Error: La busqueda por sueldo debe ser numerica." . "<BR>";
  }
  else
  {

    if(strlen($psueldo_high))
    {
      if($sWhere != "") 
        $sWhere .= " and ";
      $HasParam = true;
      $sWhere = $sWhere . "c.sueldo_min <=" . tosql($psueldo_high,"Number");
    }

    if(strlen($psueldo_low))
    {
      if($sWhere != "") 
        $sWhere .= " and ";
      $HasParam = true;
      $sWhere = $sWhere . "c.sueldo_min >=" . tosql($psueldo_low,"Number");
    }
  
  }

  $pantiguedad=get_param("antiguedad");

  if($pantiguedad=="1")
  {
      $pdatelimit = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")-1));

      if($sWhere != "") 
        $sWhere .= " and ";
      $HasParam = true;
      $sWhere = $sWhere . "c.fecha_alta <=" . tosql($pdatelimit,"Date");
  }

  $pidioma = get_param("idioma");

  if(strlen($pidioma))
  {
    if($sWhere != "") 
      $sWhere .= " and ";
    $HasParam = true;
    $sWhere = $sWhere . "i.idioma like" . tosql("%" . $pidioma . "%","Text");
    
    $sJoin=" LEFT OUTER JOIN idiomas i ON i.id_candidato=c.id_candidato ";
    $sGroup=" Group By c.id_candidato";
  }

  $pclave=get_param("clave");

  if(strlen($pclave))
  {
    if($sWhere != "") 
      $sWhere .= " and ";
    $HasParam = true;
    $sWhere = $sWhere . "(UPPER(c.extras_estudios) like" . tosql("%" . strtoupper($pclave) . "%","Text") .
                        " OR UPPER(c.logros) like" . tosql("%" . strtoupper($pclave) . "%","Text") .
                        " OR UPPER(c.intereses) like" . tosql("%" . strtoupper($pclave) . "%","Text") .                         
                        " OR UPPER(c.expectativas) like" . tosql("%" . strtoupper($pclave) . "%","Text") .                         
                        " OR UPPER(c.areas_fuertes) like" . tosql("%" . strtoupper($pclave) . "%","Text") .                         
                        " OR UPPER(c.areas_debiles) like" . tosql("%" . strtoupper($pclave) . "%","Text") .
                        " OR UPPER(car.nombre) like" . tosql("%" . strtoupper($pclave) . "%","Text") .
                        ")";
  }


  $sWhere =" WHERE " . $sWhere;

//-------------------------------
// cands Open Event begin
// cands Open Event end
//-------------------------------

//-------------------------------
// Assemble full SQL statement
//-------------------------------
  $sSQL .= $sJoin . $sWhere . $sOrder . $sGroup;
//  echo($sSQL);
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
    $fldid_candidato_nav=$db->f("c_id_Candidato");
    $fldcand_id_cand_id = $db->f("c_id_Candidato");
    $fldcand_id = $db->f("c_id_Candidato");
    $fldid_candidato_nav = $db->f("c_id_Candidato");
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
      $tpl->set_var("id_candidato_nav", tohtml($fldid_candidato_nav));
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

      if(!security_info(2))
        $tpl->set_var("Security_2_3", "");
      else
        $tpl->parse("Security_2_3", false);

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
  $tpl->set_var( "candsNoRecords", "");

  if(!security_info(2)){
    $tpl->set_var("Security_2_2", "");
  }
  else
  {
    $tpl->parse("Security_2_2", false);
  }

  $tpl->parse( "Formcands", false);
//-------------------------------
// cands Close Event begin
// cands Close Event end
//-------------------------------
}
//===============================

//===============================
// Display Search Form
//-------------------------------
function Busqueda_show()
{
  global $db;
  global $tpl;
  global $sForm;
  global $sBusquedaErr;

  $sFormTitle = "B&uacute;squeda";
  $sActionFileName = "Inicio Candidatos.php";
//-------------------------------
// Búsqueda Open Event begin
// Búsqueda Open Event end
//-------------------------------
  $tpl->set_var("FormTitle", $sFormTitle);
  $tpl->set_var("ActionPage", $sActionFileName);


//-------------------------------
// Set variables with search parameters
//-------------------------------
  $fldCandidato_nombre = strip(get_param("nombre"));    
  $fldCandidato_apaterno = strip(get_param("apaterno"));    
  $fldCandidato_amaterno = strip(get_param("amaterno"));    
  $fldCandidato_carrera = strip(get_param("carrera"));    
  $fldCandidato_sexo = strip(get_param("sexo"));    
  $fldCandidato_nivel_estudios = strip(get_param("nivel_estudios"));    
  $fldCandidato_sueldo_low = strip(get_param("sueldo_low"));    
  $fldCandidato_sueldo_high = strip(get_param("sueldo_high"));    
  $fldCandidato_antiguedad = strip(get_param("antiguedad"));    
  $fldCandidato_clave = strip(get_param("clave"));    
  $fldCandidato_idioma = strip(get_param("idioma"));    


//-------------------------------
// Búsqueda Show begin
//-------------------------------


//-------------------------------
// Búsqueda Show Event begin
// Búsqueda Show Event end
//-------------------------------
    $tpl->set_var("nombre", tohtml($fldCandidato_nombre));
    $tpl->set_var("apaterno", tohtml($fldCandidato_apaterno));
    $tpl->set_var("amaterno", tohtml($fldCandidato_amaterno));
    $tpl->set_var("carrera", genselect_fromcar("carrera",$fldCandidato_carrera));
    $tpl->set_var("sexo", genselect_fromcat("Sexo","sexo",$fldCandidato_sexo));
    $tpl->set_var("nivel_estudios", genselect_fromcat("Nivel de Estudios","nivel_estudios",$fldCandidato_nivel_estudios));
    $tpl->set_var("sueldo_low", tohtml($fldCandidato_sueldo_low));
    $tpl->set_var("sueldo_high", tohtml($fldCandidato_sueldo_high));
    $tpl->set_var("clave", tohtml($fldCandidato_clave));
    $tpl->set_var("idioma", tohtml($fldCandidato_idioma));

    if($fldCandidato_antiguedad=="0"||!strlen($fldCandidato_antiguedad))
       $tpl->set_var("check_antiguedad_0", "CHECKED");
    else
       $tpl->set_var("check_antiguedad_1", "CHECKED");
  
//-------------------------------
// Búsqueda Show end
//-------------------------------

//-------------------------------
// Búsqueda Close Event begin
// Búsqueda Close Event end
//-------------------------------
  if(strlen($sBusquedaErr))
  {
     $tpl->set_var("sBusquedaErr",$sBusquedaErr);
     $tpl->parse("Err",false);
  }
  else
  {
     $tpl->set_var("Err","");  
  }

  $tpl->parse("FormBúsqueda", false);
//===============================
}
//===============================
// Display Grid Form
//-------------------------------
function nav_show()
{
//-------------------------------
// Initialize variables  
//-------------------------------
  
  global $tpl;
  global $db;
  global $scandsErr;
  global $sBusquedaErr;

  $fldid_candidato_nav=get_param("id_candidato_nav");

  if(!strlen($fldid_candidato_nav))
  {
    $tpl->set_var("Candidato","");
    $tpl->parse("CandidatoNoSelec",false);
  }
  else
  {
    $iSQL="SELECT * FROM CANDIDATOS WHERE ID_CANDIDATO=" . tosql($fldid_candidato_nav,"number");
    $db->query($iSQL);
    $db->next_record();
    
    $fldnombre=$db->f("nombres") . " " . $db->f("apaterno") . " " . $db->f("amaterno");
    $fldrecomendado=$db->f("recomendado_por");
    $tpl->set_var("recomendado",$fldrecomendado);

    $iSQL="SELECT * FROM CARRERAS WHERE ID_CARRERA=" . $db->f("carrera");
    $db->query($iSQL);
    $db->next_record();

    $fldcarrera=$db->f("nombre");

    $tpl->set_var("carrera",$fldcarrera);
    $tpl->set_var("nombre",$fldnombre);

    $tpl->set_var("clientes",genselect_fromcli("clientes",""));
    
    $tpl->set_var("CandidatoNoSelec","");
    $tpl->parse("Candidato",false);
  }

  $tpl->set_var("FormTitle","Navegacion");
  $tpl->set_var("id_candidato_nav", tohtml($fldid_candidato_nav));
  $tpl->parse("FormNavegacion",false);
}
?>