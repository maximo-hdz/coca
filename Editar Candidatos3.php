<?php
/*********************************************************************************
 *       Filename: Editar Candidatos.php
 *       Proyecto COCA Consultores
 *       Adrian Bisiacchi
 *       PHP 4.0 & Tcandlates Escrito el 10/04/2002
 *********************************************************************************/

//-------------------------------
// Insertar Candidatos CustomIncludes begin

include ("./common.php");

// Insertar Candidatos CustomIncludes end
//-------------------------------

session_start();

//===============================
// Save Page and File Name available into variables
//-------------------------------
$filename = "Editar Candidatos3.php";
$template_filename = "Editar Candidatos3.html";
//===============================


//===============================
// Insertar Candidatos PageSecurity begin
//check_security(3);
// Insertar Candidatos PageSecurity end
//===============================

//===============================
// Insertar Candidatos Open Event begin
// Insertar Candidatos Open Event end
//===============================

//===============================
// Insertar Candidatos OpenAnyPage Event start
// Insertar Candidatos OpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

// Insertar Candidatos Show begin

//===============================
// Perform the form's action
//-------------------------------
// Initialize error variables
//-------------------------------
$sCandidatoErr = "";
$sReferenciaErr = "";

//-------------------------------
// Select the FormAction
//-------------------------------

//echo($sForm);
//echo($sAction);
switch ($sForm) {
  case "Candidato":
    Candidatos_action($sAction);
  break;
  case "Referencias":
    Referencias_action($sAction);
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

//-------------------------------
// Step through each form
//-------------------------------
Candidatos_show();

//-------------------------------
// Process page templates
//-------------------------------
//$tpl->parse("Header", false);
//-------------------------------
// Output the page to the browser
//-------------------------------

   $tpl->pparse("main", false);

// Insertar Candidatos Show end

//===============================
// Insertar Candidatos Close Event begin
// Insertar Candidatos Close Event end
//===============================
//********************************************************************************


//===============================
// Action of the Record Form
//-------------------------------
function Candidatos_action($sAction)
{
//-------------------------------
// Initialize variables  
//-------------------------------
  global $db;
  global $tpl;
  global $sForm;
  global $sCandidatoErr;
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
 if(strlen(get_param("id_Candidato")))
 {
   $TransitParams="?id_Candidato=" . get_param("id_Candidato");
   $sWhere=" WHERE id_Candidato=" . get_param("id_Candidato") . " ";
 }

//-------------------------------
// Candidatos Action begin
//-------------------------------
  $sActionFileName = "Inicio Candidatos.php";
  $sNext = "Inicio Candidatos.php";
  $sPrev = "Editar Candidatos2.php";
//-------------------------------
// CANCEL action
//-------------------------------
  if($sAction == "cancel")
  {

//-------------------------------
// Candidatoss BeforeCancel Event begin
// Candidatoss BeforeCancel Event end
//-------------------------------
    header("Location: " . $sActionFileName);
    exit;
  }
//-------------------------------


//-------------------------------
// Build WHERE statement
//-------------------------------


//-------------------------------
// Load all form fields into variables
//-------------------------------
  $flddisponibilidad = get_param("disponibilidad");
  $flddisponibilidad_residencia = get_param("disponibilidad_residencia");
  $flddisponibilidad_viaje = get_param("disponibilidad_viaje");
  $fldpuesto_des = get_param("puesto_des");
  $fldsueldo_min = get_param("sueldo_min");
  $fldsueldo_max = get_param("sueldo_max");
  $fldhorario = get_param("horario");
  $fldfuente = get_param("fuente");
 
//  echo(get_param("direccion_estado"));
// Validate fields
//-------------------------------
  if($sAction == "siguiente" || $sAction=="anterior") 
  {
//-------------------------------
// Candidatoss Check Event begin
// Candidatoss Check Event end
//-------------------------------
    if($flddisponibilidad==-1)
       $sCandidatoErr=$sCandidatoErr . tohtml("Error: Debe seleccionar la Disponibilidad.") . "<br>";       

    if($flddisponibilidad_residencia==-1)
       $sCandidatoErr=$sCandidatoErr . tohtml("Error: Debe definir si esta dispuesto a mudarse.") . "<br>";       

    if($flddisponibilidad_viaje==-1)
       $sCandidatoErr=$sCandidatoErr . tohtml("Error: Debe definir si esta dispuesto a viajar.") . "<br>";       

    if(!strlen($fldpuesto_des))
       $sCandidatoErr=$sCandidatoErr . tohtml("Error: No puede dejar el campo 'Puesto Deseado' en blanco.") . "<br>";       

    if(!strlen($fldsueldo_min))
       $sCandidatoErr=$sCandidatoErr . tohtml("Error: No puede dejar el campo 'Sueldo minimo' en blanco.") . "<br>";       

    if(!is_numeric($fldsueldo_min))
       $sCandidatoErr=$sCandidatoErr . tohtml("Error: El campo sueldo minimo debe ser numerico.") . "<br>";       

    if(!is_numeric($fldsueldo_max))
       $sCandidatoErr=$sCandidatoErr . tohtml("Error: El campo sueldo maximo debe ser numerico.") . "<br>";       

    if(!strlen($fldsueldo_max))
       $sCandidatoErr=$sCandidatoErr . tohtml("Error: No puede dejar el campo 'Sueldo deseado' en blanco.") . "<br>";       

    if($fldhorario==-1)
       $sCandidatoErr=$sCandidatoErr . tohtml("Error: Debe definir su disponibilidad de horario.") . "<br>";       

    if($fldfuente==-1)
       $sCandidatoErr=$sCandidatoErr . tohtml("Error: Debe seleccionar una opcion que describa como se entero de nosotros.") . "<br>";       


    if(strlen($sCandidatoErr)) return;
  }
//-------------------------------

//echo($fldtelefono_oficina);


//-------------------------------
// Create SQL statement
//-------------------------------
  if($sAction=="siguiente" || $sAction=="anterior" || $sAction=="update") 
  {     
    //-------------------------------
    // Candidatoss Update Event begin
    // Candidatoss Update Event end
    //-------------------------------
        $sSQL = "update Candidatos set " .
          "disponibilidad=" . tosql($flddisponibilidad, "Number") . "," .
          "disponibilidad_residencia=" . tosql($flddisponibilidad_residencia, "Number") . "," .
          "disponibilidad_viaje=" . tosql($flddisponibilidad_viaje, "Number") . "," .
          "puesto=" . tosql($fldpuesto_des, "Text") . ","  .
          "sueldo_min=" . tosql($fldsueldo_min, "Text") . ","  .
          "sueldo_max=" . tosql($fldsueldo_max, "Text") . ","  .
          "horario=" . tosql($fldhorario, "Number") . ","  .
          "fuente=" . tosql($fldfuente, "Number");
        $sSQL .=  $sWhere;
  }
//-------------------------------
//-------------------------------
// Candidatos BeforeExecute Event begin
// Candidatos BeforeExecute Event end
//-------------------------------

//-------------------------------
// Execute SQL statement
//-------------------------------
  if(strlen($sCandidatoErr)) return;

  if($bExecSQL)
    $db->query($sSQL);
//echo($sSQL);
  if($sAction=="update")
	return;
  if($sAction=="siguiente")
	header("Location: " . $sNext . $TransitParams);
  if($sAction=="anterior")
	header("Location: " . $sPrev . $TransitParams);
  exit;

//-------------------------------
// Candidatoss Action end
//-------------------------------
}

//===============================
// Action of the Language Form
//-------------------------------
function Referencias_action($sAction)
{
//-------------------------------
// Initialize variables  
//-------------------------------
  global $db;
  global $tpl;
  global $sForm;
  global $sReferenciaErr;
  $iSQL="";
//  echo($sAction);

  Candidatos_action("update");
  $fldempresa=get_param("empresa");
  $fldgiro=get_param("giro");
  $fldpuesto=get_param("ref_puesto");
  $fldfunciones=get_param("funciones");
  $fldtelefono_empresa=get_param("telefono_empresa");
  $fldfecha_ingreso=get_param("fecha_ingreso");
  $fldfecha_recorte=get_param("fecha_recorte");
  $fldsueldo_inicial=get_param("sueldo_inicial");
  $fldsueldo_final=get_param("sueldo_final");
  $fldjefe_inmediato=get_param("jefe_inmediato");

  if($sAction=="insert"){

    if(!strlen($fldempresa))
      $sReferenciaErr.= "ERROR: El campo 'Empresa' no se puede dejar en blanco." . "<BR>";

    if(!strlen($fldgiro))
      $sReferenciaErr.= "ERROR: El campo 'Giro' no se puede dejar en blanco." . "<BR>";

    if(!strlen($fldpuesto))
      $sReferenciaErr.= "ERROR: El campo 'Puesto' no se puede dejar en blanco." . "<BR>";

    if(!strlen($fldfunciones))
      $sReferenciaErr.= "ERROR: El campo 'Funciones' no se puede dejar en blanco." . "<BR>";

    if(!strlen($fldjefe_inmediato))
      $sReferenciaErr.= "ERROR: El campo 'Jefe Inmediato' no se puede dejar en blanco." . "<BR>";

    if(!ereg("[0-9,-,(,)]{1,20}",$fldtelefono_empresa))
      $sReferenciaErr.= "ERROR: El campo 'Telefono de la Empresa' es invalido." . "<BR>";

    if(!is_date($fldfecha_ingreso))
      $sReferenciaErr.= "ERROR: El campo 'Fecha de Ingreso' es invalido." . "<BR>";

    if(strtotime($fldfecha_ingreso) > mktime(0,0,0,date("m"),date("d"),date("Y")))
      $sReferenciaErr .= "Error, la fecha de ingreso no puede estar en el futuro<br>";


    if(!is_date($fldfecha_recorte))
      $sReferenciaErr.= "ERROR: El campo 'Fecha de Recorte' es invalido." . "<BR>";

    if(strtotime($fldfecha_recorte) > mktime(0,0,0,date("m"),date("d"),date("Y")))
      $sReferenciaErr .= "Error, la fecha de recorte no puede estar en el futuro<br>";

    if(strtotime($fldfecha_recorte) < strtotime($fldfecha_ingreso))
      $sReferenciaErr .= "Error, la fecha de recorte no puede ser anterior a la fecha de inicio.<br>";


    if(!is_numeric($fldsueldo_inicial))
      $sReferenciaErr.= "ERROR: El campo 'Sueldo Inicial' debe ser numerico." . "<BR>";
     
    if(!is_numeric($fldsueldo_final))
      $sReferenciaErr.= "ERROR: El campo 'Sueldo Final' debe ser numerico." . "<BR>";
  }

  if(!strlen($sReferenciaErr)){
    $fldid_candidato=get_param("id_Candidato");
    $fldid_referencia=get_param("id_referencia");
  }
  else
  {
    $fldid_candidato=strip(get_param("id_Candidato"));
    $fldid_referencia=strip(get_param("id_referencia"));
    $fldempresa=strip(get_param("empresa"));
    $fldgiro=strip(get_param("giro"));
    $fldpuesto=strip(get_param("ref_puesto"));
    $fldfunciones=strip(get_param("funciones"));
    $fldtelefono_empresa=strip(get_param("telefono_empresa"));
    $fldfecha_ingreso=strip(get_param("fecha_ingreso"));
    $fldfecha_recorte=strip(get_param("fecha_recorte"));
    $fldsueldo_inicial=strip(get_param("sueldo_inicial"));
    $fldsueldo_final=strip(get_param("sueldo_final"));
    $fldjefe_inmediato=strip(get_param("jefe_inmediato"));
  }

  if(strlen($sReferenciaErr))
    return; 

  switch($sAction)
  {
    case "insert":
      $iSQL="INSERT INTO ReferenciaS (EMPRESA, GIRO, PUESTO, FUNCIONES, TELEFONO_EMPRESA, " .
            "FECHA_INGRESO, FECHA_RECORTE, SUELDO_INICIAL, SUELDO_FINAL, " .
            "JEFE_INMEDIATO, id_Candidato) " .
            " VALUES ( " .
            tosql($fldempresa,"Text") . "," .
            tosql ($fldgiro,"Text") . "," .
            tosql ($fldpuesto,"Text") . "," .
            tosql ($fldfunciones,"Text") . "," .
            tosql ($fldtelefono_empresa,"Text") . "," .
            tosql ($fldfecha_ingreso,"Date") . "," .
            tosql ($fldfecha_recorte,"Date") . "," .
            tosql ($fldsueldo_inicial,"Number") . "," .
            tosql ($fldsueldo_final,"Number") . "," .
            tosql ($fldjefe_inmediato,"Text") . "," .
            tosql(get_param("id_Candidato"),"Number") .
            ")";      
    break;
    case "delete":
      $iSQL="DELETE FROM ReferenciaS WHERE ID_referencia=" . tosql($fldid_referencia,"Number");
    break;
  }
//  echo($iSQL);
  $db->query($iSQL);
  return;
}

//===============================
// Display Record Form
//-------------------------------
function Candidatos_show()
{
  global $db;
  global $tpl;
  global $sAction;
  global $sForm;
  global $sCandidatoErr;
  global $sReferenciaErr;
  
  $fldid_Candidato = "";
  $flddisponibilidad = "";
  $flddisponibilidad_residencia = "";
  $flddisponibilidad_viaje = "";
  $fldpuesto = "";
  $fldsueldo_min = "";
  $fldsueldo_max = "";
  $fldhorario = "";
  $fldfuente="";
  
//-------------------------------
// Candidatoss Show begin
//-------------------------------
  $sFormTitle = "Estudios";
  $sWhere = "";
  $bPK = true;
//-------------------------------
// Load primary key and form parameters
//-------------------------------
  if($sCandidatoErr == "")
  {
    $pid_Candidato=get_param("id_Candidato");
    $flddisponibilidad = get_param("disponibilidad");
    $flddisponibilidad_residencia = get_param("disponibilidad_residencia");
    $flddisponibilidad_viaje = get_param("disponibilidad_viaje");
    $fldpuesto_des = get_param("puesto_des");
    $fldsueldo_min = get_param("sueldo_min");
    $fldsueldo_max = get_param("sueldo_max");
    $fldhorario = get_param("horario");
    $fldfuente = get_param("fuente");
 
    $tpl->set_var("CandidatoError", "");
  }
  else
  {
    $pid_Candidato=strip(get_param("id_Candidato"));
    $flddisponibilidad = strip(get_param("disponibilidad"));
    $flddisponibilidad_residencia = strip(get_param("disponibilidad_residencia"));
    $flddisponibilidad_viaje = strip(get_param("disponibilidad_viaje"));
    $fldpuesto_des = strip(get_param("puesto_des"));
    $fldsueldo_min = strip(get_param("sueldo_min"));
    $fldsueldo_max = strip(get_param("sueldo_max"));
    $fldhorario = strip(get_param("horario"));
    $fldfuente = strip(get_param("fuente"));

    $tpl->set_var("sCandidatoErr", $sCandidatoErr);
    $tpl->set_var("FormTitle", $sFormTitle);
    $tpl->parse("CandidatoError", false);
  }
//-------------------------------

//-------------------------------
// Load all form fields

//-------------------------------

//-------------------------------
// Build WHERE statement
//-------------------------------
  
  if( !strlen($pid_Candidato)) $bPK = false;
  
  $sWhere .= "id_Candidato=" . tosql($pid_Candidato, "Number");
  $tpl->set_var("PK_id_Candidato", $pid_Candidato);
//-------------------------------
//-------------------------------
// Candidatoss Open Event begin
// Candidatoss Open Event end
//-------------------------------

  $tpl->set_var("FormTitle", $sFormTitle);

//-------------------------------
// Build SQL statement and execute query
//-------------------------------
  $sSQL = "select * from Candidatos where " . $sWhere;
  // Execute SQL statement
  $db->query($sSQL);
  $bIsUpdateMode = ($bPK && !($sAction == "insert" && $sForm == "Candidato") && $db->next_record());
//-------------------------------

//-------------------------------
// Load all fields into variables from recordset or input parameters
//-------------------------------
  if($bIsUpdateMode)
  {
    $fldid_Candidato = $db->f("id_Candidato");
//-------------------------------
// Load data from recordset when form displayed first time
//-------------------------------
    if($sCandidatoErr == "") 
    {
      $flddisponibilidad = $db->f("disponibilidad");
      $flddisponibilidad_residencia = $db->f("disponibilidad_residencia");
      $flddisponibilidad_viaje = $db->f("disponibilidad_viaje");
      $fldpuesto_des = $db->f("puesto");
      $fldsueldo_min = $db->f("sueldo_min");
      $fldsueldo_max = $db->f("sueldo_max");
      $fldhorario = $db->f("horario");
      $fldfuente = $db->f("fuente");
    }
//-------------------------------
// Candidatoss ShowEdit Event begin
// Candidatoss ShowEdit Event end
//-------------------------------
  }
  else
  {
    if($sCandidatoErr == "")
    {
      $fldid_Candidato = tohtml(get_param("id_Candidato"));
    }
//-------------------------------
// Candidatoss ShowInsert Event begin
// Candidatoss ShowInsert Event end
//-------------------------------
  }
  $tpl->parse("CandidatoCancel", false);
//-------------------------------
// Candidatoss Show Event begin
// Candidatoss Show Event end
//-------------------------------

//-------------------------------
// Show form field
//-------------------------------
    $tpl->set_var("id_Candidato",  tohtml(get_param("id_Candidato")));
    $tpl->set_var("disponibilidad", genselect_fromcat("Disponibilidad","disponibilidad",$flddisponibilidad));
    $tpl->set_var("disponibilidad_residencia", genselect_fromcat("SiNo","disponibilidad_residencia",$flddisponibilidad_residencia));
    $tpl->set_var("disponibilidad_viaje", genselect_fromcat("SiNo","disponibilidad_viaje",$flddisponibilidad_viaje));
    $tpl->set_var("puesto_des",tohtml($fldpuesto_des));
    $tpl->set_var("sueldo_min",tohtml($fldsueldo_min));
    $tpl->set_var("sueldo_max",tohtml($fldsueldo_max));
    $tpl->set_var("horario", genselect_fromcat("Disponibilidad de Horario","horario",$fldhorario));
    echo($fldfuente);
    $tpl->set_var("fuente", genselect_fromfue("fuente",$fldfuente));

    if(strlen($sReferenciaErr)){
      $tpl->set_var("sReferenciaErr",$sReferenciaErr);
      $tpl->parse("ReferenciaError",false);}
    else
      $tpl->set_var("ReferenciaError","");
      
    $iSQL="SELECT * FROM ReferenciaS WHERE ID_CANDIDATO=" . get_param("id_Candidato");
    $db->query($iSQL);

    $next_record=$db->next_record();
    if(!$next_record){
       $tpl->set_var("DReferencias","");
       $tpl->parse("ReferenciasNoRecords",false);}
    else
       $tpl->set_var("ReferenciasNoRecords","");

    while($next_record)
    {
      $fldempresa=$db->f("empresa");
      $fldgiro=$db->f("giro");
      $fldpuesto=$db->f("puesto");
      $fldfunciones=$db->f("funciones");
      $fldtelefono_empresa=$db->f("telefono_empresa");
      $fldfecha_ingreso=$db->f("fecha_ingreso");
      $fldfecha_recorte=$db->f("fecha_recorte");
      $fldsueldo_inicial=$db->f("sueldo_inicial");
      $fldsueldo_final=$db->f("sueldo_final");
      $fldjefe_inmediato=$db->f("jefe_inmediato");
      $fldid_referencia=$db->f("id_referencia");

      $tpl->set_var("empresa", $fldempresa);
      $tpl->set_var("giro", $fldgiro);
      $tpl->set_var("puesto", $fldpuesto);
      $tpl->set_var("funciones", $fldfunciones);
      $tpl->set_var("telefono_empresa", $fldtelefono_empresa);
      $tpl->set_var("fecha_ingreso", $fldfecha_ingreso);
      $tpl->set_var("fecha_recorte", $fldfecha_recorte);
      $tpl->set_var("sueldo_inicial", $fldsueldo_inicial);
      $tpl->set_var("sueldo_final", $fldsueldo_final);
      $tpl->set_var("jefe_inmediato", $fldjefe_inmediato);

      $tpl->set_var("referencia_elim","Eliminar Referencia");
      $tpl->set_var("id_referencia", $fldid_referencia);      

      $tpl->parse("DReferencias",true);

      $next_record=$db->next_record();
    }
    if(strlen($sReferenciaErr)){
      $tpl->set_var("empresa", get_param("empresa"));
      $tpl->set_var("giro", get_param("giro"));
      $tpl->set_var("ref_puesto", get_param("ref_puesto"));
      $tpl->set_var("funciones", get_param("funciones"));
      $tpl->set_var("telefono_empresa", get_param("telefono_empresa"));
      $tpl->set_var("fecha_ingreso", get_param("fecha_ingreso"));
      $tpl->set_var("fecha_recorte", get_param("fecha_recorte"));
      $tpl->set_var("sueldo_inicial", get_param("sueldo_inicial"));
      $tpl->set_var("sueldo_final", get_param("sueldo_final"));
      $tpl->set_var("jefe_inmediato", get_param("jefe_inmediato"));}
    else
    {
      $tpl->set_var("empresa", "");
      $tpl->set_var("giro", "");
      $tpl->set_var("ref_puesto", "");
      $tpl->set_var("funciones", "");
      $tpl->set_var("telefono_empresa", "");
      $tpl->set_var("fecha_ingreso", "");
      $tpl->set_var("fecha_recorte", "");
      $tpl->set_var("sueldo_inicial", "");
      $tpl->set_var("sueldo_final", "");
      $tpl->set_var("jefe_inmediato", "");
    }

//    echo(get_param("referrer"));
    if(get_param("referrer")==2)
    {
      $fldScript="<SCRIPT LANGUAGE=JavaScript> function Salir(){document.Candidato.action='Inicio Clientes.php';;}</SCRIPT>";
    }
    else
    {
      $fldScript="<SCRIPT LANGUAGE=JavaScript> function Salir(){document.Candidato.action='Inicio Candidatos.php';;}</SCRIPT>";
    }

    $tpl->set_var("referrer", get_param("referrer"));
    $tpl->set_var("Script",$fldScript);    


    $tpl->parse("FormCandidato", false);

//-------------------------------
// Candidatoss Close Event begin
// Candidatoss Close Event end
//-------------------------------

//-------------------------------
// Candidatoss Show end
//-------------------------------
}
//===============================
?>
