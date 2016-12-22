<?php
/*********************************************************************************
 *       Filename: Inicio Clientes.php
 *       Proyecto Coca Consultores
 *       Adrian Bisiacchi 
 *       PHP 4.0 & Templates Escrito el 07/04/2002
 *********************************************************************************/

//-------------------------------
// Tabla Clientes CustomIncludes begin

include ("./common.php");

// Tabla Clientes CustomIncludes end
//-------------------------------


//-------------------------------
// Tabla Clientes CustomFunctions begin

function avoid_nulls($string)
{
  if(!strlen($string))
    return "????";
  else
    return $string;
}

// Tabla Clientes CustomFunctions end
//-------------------------------

session_start();

//===============================
// Save Page and File Name available into variables
//-------------------------------
$filename = "Inicio Clientes.php";
$template_filename = "Inicio Clientes.html";
//===============================



//===============================
// Inicio Clientes PageSecurity begin
check_security(2);
// Inicio Clientes PageSecurity end
//===============================

//===============================
// Inicio Clientes Open Event begin
// Inicio Clientes Open Event end
//===============================

//===============================
// Inicio Clientes OpenAnyPage Event start
// Inicio Clientes OpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

if($sForm=="Navegacion")
{
   NavAction($sAction);
}



// Inicio Clientes Show begin

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
Check_Reminders();Busqueda_show();Navegacion_show();Clientes_show();

//-------------------------------
// Process page templates
//-------------------------------
//$tpl->parse("Header", false);
//-------------------------------
// Output the page to the browser
//-------------------------------
$tpl->pparse("main", false);
// Inicio Clientes Show end

function NavAction($action)
{
  $sTransit="";
  if($action=="EditarContacto" or $action=="EliminarContacto")
  {
     $sTransit="?id_contacto=" . get_param("id_contacto_sel");
     header("Location: Editar Contacto.php" . $sTransit);
  }


  if($action=="AgregarContacto")
	{
          header("Location: Editar Contacto.php?id_cliente=" . get_param("id_cliente_nav"));
	}
}

//===============================
// Inicio Clientes Close Event begin
// Inicio Clientes Close Event end
//===============================
//********************************************************************************


//===============================
// Display Grid Form
//-------------------------------
function Clientes_show()
{
//-------------------------------
// Initialize variables  
//-------------------------------
  
  global $tpl;
  global $db;
  global $sClientesErr;
  $sWhere = "";
  $sSerWhere = "";
  $sFormParams = "";
  $sOrder = "";
  $sSQL = "";
  $sFormTitle = "Clientes";
  $HasParam = false;
  $iRecordsPerPage = 20;
  $iCounter = 0;
  $iPage = 0;
  $bEof = false;
  $iSort = "";
  $iSorted = "";
  $sDirection = "";
  $sSortParams = "";
  $sActionFileName = "Anadir Clientes.php";

  $tpl->set_var("TransitParams", "id_cliente=" . tourl(get_param("id_cliente")) . "&nombre=" . tourl(get_param("nombre")) . "&razon_social=" . tourl(get_param("razon_social")) . "&direccion_delegacion=" . tourl(get_param("direccion_delegacion")) . "&direccion_estado=" . tourl(get_param("direccion_estado")));
  $sFormParams="id_cliente=" . tourl(get_param("id_cliente")) . "&nombre=" . tourl(get_param("nombre")) . "&razon_social=" . tourl(get_param("razon_social")) . "&direccion_delegacion=" . tourl(get_param("direccion_delegacion")) . "&direccion_estado=" . tourl(get_param("direccion_estado"));
  if(get_param("estatus")!=-1)
     $sFormParams=$sFormParams . "&estatus=" . tourl(get_param("estatus"));
  if(get_param("ser_recsel"))
     $sFormParams.="&ser_recsel=" . tourl(get_param("ser_recsel"));
  if(get_param("ser_capaci"))
     $sFormParams.="&ser_capaci=" . tourl(get_param("ser_capaci"));
  if(get_param("ser_conadm"))
     $sFormParams.="&ser_conadm=" . tourl(get_param("ser_conadm"));
  if(get_param("ser_concon"))
     $sFormParams.="&ser_concon=" . tourl(get_param("ser_concon"));
  if(get_param("ser_confin"))
     $sFormParams.="&ser_confin=" . tourl(get_param("ser_confin"));
  if(get_param("ser_outadm"))
     $sFormParams.="&ser_outadm=" . tourl(get_param("ser_outadm"));
  if(get_param("ser_admnom"))
     $sFormParams.="&ser_admnom=" . tourl(get_param("ser_admnom"));
  if(get_param("ser_asecal"))
     $sFormParams.="&ser_asecal=" . tourl(get_param("ser_asecal"));

  $tpl->set_var("FormParams", $sFormParams."&");

//-------------------------------
// Build WHERE statement
//-------------------------------
  $pid_cliente = get_param("id_cliente");

  if(strlen($pid_cliente))
  {
    $HasParam = true;
    $sWhere = $sWhere . "c.id_cliente=" . tosql($pid_cliente,"Number");
  }

  $pnombre = get_param("nombre");

  if(strlen($pnombre))
  {
    if($sWhere != "") 
      $sWhere .= " and ";
    $HasParam = true;
    $sWhere = $sWhere . "c.nombre like" . tosql("%" . $pnombre . "%","Text");
  }

  $prazon_social = get_param("razon_social");
  if(strlen($prazon_social))
  {
    if($sWhere != "") 
      $sWhere .= " and ";
    $HasParam = true;
    $sWhere = $sWhere . "c.razon_social like " . tosql("%" . $prazon_social . "%", "Text");
  }

  $pdireccion_delegacion = get_param("direccion_delegacion");
  if(strlen($pdireccion_delegacion))
  {
    if($sWhere != "") 
      $sWhere .= " and ";
    $HasParam = true;
    $sWhere = $sWhere . "c.direccion_delegacion like " . tosql("%" . $pdireccion_delegacion . "%", "Text");
  }

  $pdireccion_estado = get_param("direccion_estado");
  if(strlen($pdireccion_estado))
  {
    if($sWhere != "") 
      $sWhere .= " and ";
    $HasParam = true;
    $sWhere = $sWhere . "c.direccion_estado like " . tosql("%" . $pdireccion_estado . "%", "Text");
  }

  $pemp = get_param("emp");
  if(strlen($pemp) && $pemp != -1)
  {
    if($sWhere != "") 
      $sWhere .= " and ";
    $HasParam = true;
    $sWhere = $sWhere . "rel.id_empleado =" . tosql($pemp, "Number");
    $sJoin ="left outer join " .
	    " relclientesempleados rel " .
	    " on c.id_cliente=rel.id_cliente ";
    $sgroupBy = " group by rel.id_cliente ";
  }
  else
  {
    $sJoin ="";
    $sgroupBy = "";

  }


  $pser_recsel = get_param("ser_recsel");
  if($pser_recsel)
  {
    if($sSerWhere != "") 
      $sSerWhere .= " or ";
    else
      $sSerWhere .= "(";
    $HasParam = true;
    $sSerWhere = $sSerWhere . "c.ser_recsel=" . tosql($pser_recsel, "Bit");
  }

  $pser_capaci = get_param("ser_capaci");
  if($pser_capaci)
  {
    if($sSerWhere != "") 
      $sSerWhere .= " or ";
    else
      $sSerWhere .= "(";
    $HasParam = true;
    $sSerWhere = $sSerWhere . "c.ser_capaci=" . tosql($pser_capaci, "Bit");
  }
  $pser_conadm = get_param("ser_conadm");
  if($pser_conadm)
  {
    if($sSerWhere != "") 
      $sSerWhere .= " or ";
    else
      $sSerWhere .= "(";
    $HasParam = true;
    $sSerWhere = $sSerWhere . "c.ser_conadm=" . tosql($pser_conadm, "Bit");
  }
  $pser_confin = get_param("ser_confin");
  if($pser_confin)
  {
    if($sSerWhere != "") 
      $sSerWhere .= " or ";
    else
      $sSerWhere .= "(";
    $HasParam = true;
    $sSerWhere = $sSerWhere . "c.ser_confin=" . tosql($pser_confin, "Bit");
  }
  $pser_concon = get_param("ser_concon");
  if($pser_concon)
  {
    if($sSerWhere != "") 
      $sSerWhere .= " or ";
    else
      $sSerWhere .= "(";
    $HasParam = true;
    $sSerWhere = $sSerWhere . "c.ser_concon=" . tosql($pser_concon, "Bit");
  }
  $pser_outadm = get_param("ser_outadm");
  if($pser_outadm)
  {
    if($sSerWhere != "") 
      $sSerWhere .= " or ";
    else
      $sSerWhere .= "(";
    $HasParam = true;
    $sSerWhere = $sSerWhere . "c.ser_outadm=" . tosql($pser_outadm, "Bit");
  }

  $pser_admnom = get_param("ser_admnom");
  if($pser_admnom)
  {
    if($sSerWhere != "") 
      $sSerWhere .= " or ";
    else
      $sSerWhere .= "(";
    $HasParam = true;
    $sSerWhere = $sSerWhere . "c.ser_admnom=" . tosql($pser_admnom, "Bit");
  }
  $pser_asecal = get_param("ser_asecal");
  if($pser_asecal)
  {
    if($sSerWhere != "") 
      $sSerWhere .= " or ";
    else
      $sSerWhere .= "(";
    $HasParam = true;
    $sSerWhere = $sSerWhere . "c.ser_asecal=" . tosql($pser_asecal, "Bit");
  }

  if($sSerWhere!="")
    $sSerWhere=$sSerWhere.")";

  $pestatus = get_param("estatus");
  if(strlen($pestatus)&&($pestatus!=-1))
  {
    if($sWhere != "") 
      $sWhere .= " and ";
    $HasParam = true;
    $sWhere = $sWhere . "c.estatus=" . tosql($pestatus, "Number");
  }

  if($sSerWhere != "")
  {
    if($sWhere != "")
     $sWhere.=" AND ";
     $sWhere .= $sSerWhere;}

  if($HasParam or ($sSerWhere != ""))
    $sWhere = " WHERE (" . $sWhere ;


  if(strlen($sWhere))  
    $sWhere.= ")";


//-------------------------------
// Build ORDER BY statement
//-------------------------------
  $sOrder = " order by c.urgente DESC,c.id_cliente ASC";
  $iSort = get_param("FormClientes_Sorting");
  $iSorted = get_param("FormClientes_Sorted");
  if(!$iSort)
  {
    $tpl->set_var("Form_Sorting", "");
  }
  else
  {
    if($iSort == $iSorted)
    {
      $tpl->set_var("Form_Sorting", "");
      $sDirection = " DESC";
      $sSortParams = "FormClientes_Sorting=" . $iSort . "&FormClientes_Sorted=" . $iSort . "&";
    }
    else
    {
      $tpl->set_var("Form_Sorting", $iSort);
      $sDirection = " ASC";
      $sSortParams = "FormClientes_Sorting=" . $iSort . "&FormClientes_Sorted=" . "&";
    }
    if ($iSort == 1) $sOrder = " order by c.urgente DESC, c.id_cliente" . $sDirection;
    if ($iSort == 2) $sOrder = " order by c.urgente DESC, c.nombre" . $sDirection;
    if ($iSort == 3) $sOrder = " order by c.urgente DESC, c.razon_social" . $sDirection;
    if ($iSort == 4) $sOrder = " order by c.urgente DESC, c.direccion_delegacion" . $sDirection;
    if ($iSort == 5) $sOrder = " order by c.urgente DESC, c.direccion_estado" . $sDirection;
    if ($iSort == 6) $sOrder = " order by c.urgente DESC, c.estatus" . $sDirection;
  }

  $groupBy=" GROUP BY rel.id_cliente ";

//-------------------------------
// Build base SQL statement
//-------------------------------
  
  $sSQL = "SELECT c.id_cliente as c_id_cliente, " . 
    "c.nombre as c_nombre, " . 
    "c.razon_social as c_razon_social, " . 
    "c.direccion_delegacion as c_direccion_delegacion, " . 
    "c.direccion_estado as c_direccion_estado, " . 
    "c.estatus as c_estatus, " . 
    "c.urgente as c_urgente " .
    " from clientes c " . $sJoin;
//-------------------------------

//-------------------------------
// Clientes Open Event begin
// Clientes Open Event end
//-------------------------------

//-------------------------------
// Assemble full SQL statement
//-------------------------------
  $sSQL .= $sWhere . $sgroupBy . $sOrder;
//-------------------------------

  $tpl->set_var("FormTitle", $sFormTitle);

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
    $tpl->set_var("DListClientes", "");
    $tpl->parse("ClientesNoRecords", false);
    $tpl->set_var("ClientesNavigator", "");
    $tpl->parse("FormClientes", false);
    return;
  }
//-------------------------------

//-------------------------------
// Prepare the lists of values
//-------------------------------
  
  $aCliente_estatus = split(";","0;Sin Atacar;1;Atacado por Primera Vez;2;Ya Contactado/Visitado");
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
  $iPage = get_param("FormClientes_Page");
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
    $fldCliente_id_URLLink = "Inicio Clientes.php";
    $fldCliente_id_Cliente_id = $db->f("c_id_cliente");
    $fldCliente_id = $db->f("c_id_cliente");
    $fldCliente_nombre = avoid_nulls($db->f("c_nombre"));    
    $fldCliente_razon_social = avoid_nulls($db->f("c_razon_social"));    
    $fldCliente_direccion_delegacion = avoid_nulls($db->f("c_direccion_delegacion"));    
    $fldCliente_direccion_estado = avoid_nulls($db->f("c_direccion_estado"));    
    $fldCliente_estatus = avoid_nulls($db->f("c_estatus"));    
    $fldCliente_estatus = get_lov_value($fldCliente_estatus, $aCliente_estatus);
    $fldCliente_urgente = $db->f("c_urgente");
    $fldCliente_selec= "Seleccionar";

    $next_record = $db->next_record();
    
//-------------------------------
// Clientes Show begin
//-------------------------------

//-------------------------------
// Clientes Show Event begin
// Clientes Show Event end
//-------------------------------

//-------------------------------
// Replace Template fields with database values
//-------------------------------
    
      $tpl->set_var("id_cliente", tohtml($fldCliente_id));
      $tpl->set_var("cliente_sel", tohtml($fldCliente_selec));
      $tpl->set_var("id_cliente_url", $fldCliente_id_URLLink);
      $tpl->set_var("prm_id_cliente", urlencode($fldCliente_id));
      $tpl->set_var("nombre", tohtml($fldCliente_nombre));
      $tpl->set_var("razon_social", tohtml($fldCliente_razon_social));
      $tpl->set_var("direccion_delegacion", tohtml($fldCliente_direccion_delegacion));
      $tpl->set_var("direccion_estado", tohtml($fldCliente_direccion_estado));
      $tpl->set_var("estatus", tohtml($fldCliente_estatus));
      if($fldCliente_urgente==1){
	      $tpl->set_var("color","red");}
      else{
	      $tpl->set_var("color","");}
      $tpl->parse("DListClientes", true);
//-------------------------------
// Clientes Show end
//-------------------------------

//-------------------------------
// Move to the next record and increase record counter
//-------------------------------
    
    $iCounter++;
  }

  // Clientes Navigation begin
  $bEof = $next_record;
  // Parse Navigator
  if(!$bEof && $iPage == 1)
    $tpl->set_var("ClientesNavigator", "");
  else 
  {
    if(!$bEof)
      $tpl->set_var("ClientesNavigatorLastPage", "_");
    else
      $tpl->set_var("NextPage", ($iPage + 1));
    if($iPage == 1)
      $tpl->set_var("ClientesNavigatorFirstPage", "_");
    else
      $tpl->set_var("PrevPage", ($iPage - 1));
    $tpl->set_var("ClientesCurrentPage", $iPage);
    $tpl->parse("ClientesNavigator", false);
  }

//-------------------------------
// Clientes Navigation end
//-------------------------------

//-------------------------------
// Finish form processing
//-------------------------------
  $tpl->set_var( "ClientesNoRecords", "");
  $tpl->parse( "FormClientes", false);
//-------------------------------
// Clientes Close Event begin
// Clientes Close Event end
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
  $sFormTitle = "B&uacute;squeda";
  $sActionFileName = "Inicio Clientes.php";
//-------------------------------
// Búsqueda Open Event begin
// Búsqueda Open Event end
//-------------------------------
  $tpl->set_var("FormTitle", $sFormTitle);
  $tpl->set_var("ActionPage", $sActionFileName);


//-------------------------------
// Set variables with search parameters
//-------------------------------
  $fldCliente_id = strip(get_param("id_cliente"));
  $fldCliente_nombre = strip(get_param("nombre"));    
  $fldCliente_razon_social = strip(get_param("razon_social"));    
  $fldCliente_direccion_delegacion = strip(get_param("direccion_delegacion"));    
  $fldCliente_direccion_estado = strip(get_param("direccion_estado"));    
  $fldCliente_estatus = strip(get_param("estatus"));    
  $fldCliente_ser_recsel = strip(get_param("ser_recsel"));
  $fldCliente_ser_capaci = strip(get_param("ser_capaci"));
  $fldCliente_ser_conadm = strip(get_param("ser_conadm"));
  $fldCliente_ser_confin = strip(get_param("ser_confin"));
  $fldCliente_ser_concon = strip(get_param("ser_concon"));
  $fldCliente_ser_outadm = strip(get_param("ser_outadm"));
  $fldCliente_ser_asecal = strip(get_param("ser_asecal"));
  $fldCliente_ser_admnom = strip(get_param("ser_admnom"));
  $fldCliente_emp	 = strip(get_param("emp"));

//-------------------------------
// Búsqueda Show begin
//-------------------------------


//-------------------------------
// Búsqueda Show Event begin
// Búsqueda Show Event end
//-------------------------------
    $tpl->set_var("id_cliente", tohtml($fldCliente_id));
    $tpl->set_var("nombre", tohtml($fldCliente_nombre));
    $tpl->set_var("razon_social", tohtml($fldCliente_razon_social));
    $tpl->set_var("direccion_delegacion", tohtml($fldCliente_direccion_delegacion));
    $tpl->set_var("direccion_estado", tohtml($fldCliente_direccion_estado));
    $tpl->set_var("estatus", genselect_fromcat("Estatus del Cliente","estatus",$fldCliente_estatus));
    if($fldCliente_ser_recsel)
	    $tpl->set_var("chk_ser_recsel","CHECKED");
    if($fldCliente_ser_capaci)   
	    $tpl->set_var("chk_ser_capaci","CHECKED");
    if($fldCliente_ser_conadm)
	    $tpl->set_var("chk_ser_conadm","CHECKED");
    if($fldCliente_ser_confin)
	    $tpl->set_var("chk_ser_confin","CHECKED");
    if($fldCliente_ser_concon)
	    $tpl->set_var("chk_ser_concon","CHECKED");
    if($fldCliente_ser_outadm)
	    $tpl->set_var("chk_ser_outadm","CHECKED");
    if($fldCliente_ser_admnom)
	    $tpl->set_var("chk_ser_admnom","CHECKED");
    if($fldCliente_ser_asecal)
	    $tpl->set_var("chk_ser_asecal","CHECKED");
    if (strlen($fldCliente_emp) == 0)
	    $tpl->set_var("empleados",genselect_fromemps("emp",null));
    else
	    $tpl->set_var("empleados",genselect_fromemps("emp",$fldCliente_emp));

  
//-------------------------------
// Búsqueda Show end
//-------------------------------

//-------------------------------
// Búsqueda Close Event begin
// Búsqueda Close Event end
//-------------------------------
  $tpl->parse("FormBúsqueda", false);
//===============================
}

//===============================
// Display Search Form
//-------------------------------
function Navegacion_show()
{
  global $db;
  global $tpl;
  global $sForm;
  $sFormTitle = "Navegaci&oacute;n";
  $sDetailPage = "Editar Cliente.php";
//-------------------------------
// Búsqueda Open Event begin
// Búsqueda Open Event end
//-------------------------------
  $tpl->set_var("FormTitle", $sFormTitle);
  $tpl->set_var("ActionPage", $sActionFileName);


//-------------------------------
// Set variables with search parameters
//-------------------------------
  $fldCliente_id = strip(get_param("id_cliente_sel"));

  if(strlen($fldCliente_id))
  {

    $SQL="SELECT * FROM clientes WHERE id_cliente=$fldCliente_id";

    $db->query($SQL);
    $next_record = $db->next_record();


    $fldCliente_nombre = $db->f("nombre");    
    $fldCliente_razon_social = $db->f("razon_social");    
    $fldid_contacto = get_param("id_contacto_sel");


//-------------------------------
// Búsqueda Show begin
//-------------------------------


//-------------------------------
// Búsqueda Show Event begin
// Búsqueda Show Event end
//-------------------------------
      $tpl->set_var("nombre", tohtml($fldCliente_nombre));
      $tpl->set_var("razon_social", tohtml($fldCliente_razon_social));
      $tpl->set_var("id_cliente_nav", tohtml($fldCliente_id));	
      $tpl->set_var("id_cliente", tohtml($fldCliente_id));	
      $tpl->set_var("DetailPage", tohtml($sDetailPage));
      $tpl->set_var("NavigatePage", tohtml($sDetailPage));
      $test=genselect_fromcon($fldCliente_id,"id_contacto_sel",$fldid_contacto);
      if($test != null)
	      $tpl->set_var("disp_contactos", $test);
      else
      {
              $tpl->set_var("FldEditContacto","");
	      $tpl->set_var("disp_contactos","<font style=\"font-size: 10pt; color: #000000; font-family: Arial, Tahoma, Verdana, Helvetica;\">No hay contactos registrados para este cliente.</font>");
      }
//-------------------------------
// Búsqueda Show end
//-------------------------------

//-------------------------------
// Búsqueda Close Event begin
// Búsqueda Close Event end
//-------------------------------
  if(!security_info(3)){
	$tpl->set_var("FldDeleteContacto","");
  }
  else 
  {
      $tpl->parse("FldDeleteContacto", false);   
  }

    $tpl->parse("FldCliente", false);
    $tpl->parse("FldAltaCliente", false);    
   
  if(!security_info(3)){
	$tpl->set_var("FldBajaCliente","");
  }
  else 
  {
      $tpl->parse("FldBajaCliente", false);   
  }
 $tpl->parse("FldEditaCliente", false);        
  }
  else
  {
    $tpl->set_var("FldCliente","");
    $tpl->set_var("FldBajaCliente","");
    $tpl->set_var("FldEditaCliente","");
    $tpl->set_var("NavigatePage", tohtml($sDetailPage));
  }


    $tpl->parse("FormNavegacion", false);

//===============================
}

function Check_Reminders()
{
  global $db;
  global $db2;

  $SQL="UPDATE clientes set urgente=0";
  $db->query($SQL);

  $SQL="SELECT id_cliente FROM recordatorios WHERE fecha_aplicacion <= CURRENT_DATE AND estatus=0";
  $db->query($SQL);
   
  $next_record = $db->next_record();
  if($next_record)
  {
    while($next_record){
      $SQL="UPDATE clientes set urgente=1 WHERE id_cliente=" . $db->f("id_cliente"); 
      $db2->query($SQL);      

      $next_record = $db->next_record();
    }
  }  

}

//----------------------------------------
//Generate an HTML select statement from contacts
//----------------------------------------

function genselect_fromcon($id_cliente,$name,$default)
{
  global $db;
  $selstring="";

//-----------------------------------------
//  Test title for parenthood
//-----------------------------------------

   $test=get_db_value("SELECT COUNT(*) FROM contactos WHERE id_cliente=" . tosql($id_cliente,"Number") . " AND estatus=1 Order BY nombre");  
   if($test==0)
     return null;

//------------------------------------------
// Generate header
//------------------------------------------

   if(!strlen($default))
      $selected="SELECTED";
   else
      $selected="";

   $selstring.="<SELECT NAME=\"" . tohtml($name) . "\">";
   $selstring.="<OPTION VALUE=\"-1\" " .
               tohtml($selected) . "><--Seleccione una Opci&oacute;n--></OPTION>";

//-----------------------------------------
//  Obtain records from Dbase
//-----------------------------------------
 
   $sSQL="SELECT * FROM contactos WHERE id_cliente=" . tosql($id_cliente,"Number") . 
         " AND estatus=1 Order BY nombre";
   $db->query($sSQL);
   $next_record=$db->next_record();
   while($next_record)
   {
     if($db->f("id_contacto")==$default)
       $selected="SELECTED";
     else
       $selected="";
     $selstring.="<OPTION VALUE=\"" . tohtml($db->f("id_contacto")) . 
                 "\" " . tohtml($selected) . 
                 ">" . tohtml($db->f("nombre")) . "</OPTION>";
     $next_record=$db->next_record();      
   }
//-----------------------------------------
//  Generate Footer
//-----------------------------------------
   $selstring.="</SELECT>";
   return $selstring;
}


?>