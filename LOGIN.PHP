<?php
/*********************************************************************************
 *       Filename: Login.php
 *       Proyecto Dennis Crowley
 *       Victor Medrano - NPS México - Colabora Adrian Bisiacchi
 *       PHP 4.0 & Templates Escrito el 04/03/2002
 *********************************************************************************/

//-------------------------------
// Login CustomIncludes begin

include ("./common.php");

// Login CustomIncludes end
//-------------------------------

session_start();

//===============================
// Save Page and File Name available into variables
//-------------------------------
$filename = "Login.php";
$template_filename = "Login.html";
//===============================



//===============================
// Login PageSecurity begin
// Login PageSecurity end
//===============================

//===============================
// Login Open Event begin
// Login Open Event end
//===============================

//===============================
// Login OpenAnyPage Event start
// Login OpenAnyPage Event end
//===============================

//===============================
//Save the name of the form and type of action into the variables
//-------------------------------
$sAction = get_param("FormAction");
$sForm = get_param("FormName");
//===============================

// Login Show begin

//===============================
// Perform the form's action
//-------------------------------
// Initialize error variables
//-------------------------------
$sLoginErr = "";
$sLoginMsg = "";

if(strlen(get_param("ErrMsg")))
  $sLoginErr=get_param("ErrMsg") . "<BR>";


//-------------------------------
// Select the FormAction
//-------------------------------
switch ($sForm) {
  case "Login":
    Login_action($sAction);
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
Login_show();

//-------------------------------
// Process page templates
//-------------------------------
//$tpl->parse("Header", false);
//-------------------------------
// Output the page to the browser
//-------------------------------
$tpl->pparse("main", false);
// Login Show end

//===============================
// Login Close Event begin
// Login Close Event end
//===============================
//********************************************************************************


//===============================
// Login Form Action
//-------------------------------
function Login_action($sAction)
{
  global $db;
  global $tpl;
  global $sLoginErr;
  global $sLoginMsg;
  global $filename;

  switch(strtolower($sAction))
  {
    case "login":

//-------------------------------
// Login Login begin
//-------------------------------
      $sLogin = get_param("login");
      $sPassword = get_param("password");
      $db->query("SELECT id_empleado,nivel_acceso FROM empleados WHERE login =" . tosql($sLogin, "Text") . " AND password=" . tosql($sPassword, "Text") . " AND ESTATUS=1");
      $is_passed = $db->next_record();

//-------------------------------
// Login OnLogin Event begin
// Login OnLogin Event end
//-------------------------------
      if($is_passed)
      {
//-------------------------------
// Login and password passed
//-------------------------------
        set_session("UserID", $db->f("id_empleado"));
        set_session("UserRights", $db->f("nivel_acceso"));
	check_security($is_passed["nivel_acceso"]);
        $sPage = get_param("ret_page");
        if (strlen($sPage))
        {
          header("Location: " . $sPage);
          exit;
        }
        else
        {
          header("Location: Login.php");
          exit;
        }
        $sLoginMsg="El usuario " . get_param("login") . "se ha registrado en el sistema.";
      }
      else
      {
        $sLoginErr = "El Login o Password son incorrectos.";
      }
//-------------------------------
// Login Login end
//-------------------------------
    break;
    case "logout":
//-------------------------------
// Logout action
//-------------------------------
//-------------------------------
// Login Logout begin
//-------------------------------

//-------------------------------
// Login OnLogout Event begin
// Login OnLogout Event end
//-------------------------------
      session_unregister("UserID");
      session_unregister("UserRights");
//-------------------------------
// Login Logout end
//-------------------------------
        $sLoginMsg="El usuario " . get_param("login") . " ha salido del sistema.";
    break;
  }
}
//===============================


//===============================
// Display Login Form
//-------------------------------
function Login_show()
{
  global $tpl;
  global $sLoginErr;
  global $sLoginMsg;
  global $db;
  $sFormTitle = "Login";

//-------------------------------
// Login Show begin
//-------------------------------

//-------------------------------
// Login Open Event begin
// Login Open Event end
//-------------------------------
  $tpl->set_var("FormTitle", $sFormTitle);
  $tpl->set_var("sLoginErr", $sLoginErr);
  $tpl->set_var("ret_page", get_param("ret_page"));
//-------------------------------
// Login BeforeShow Event begin
// Login BeforeShow Event end
//-------------------------------
    $tpl->set_var("login", strip(tohtml(get_param("login"))));
    if($sLoginErr == "")
    {
      $tpl->set_var("LoginError", "");
      $tpl->set_var("sLoginMsg", $sLoginMsg);
      $tpl->parse("LoginMessage",false);
    }
    else
    {
      $tpl->set_var("LoginMessage","");
      $tpl->set_var("sLoginErr", $sLoginErr);
      $tpl->parse("LoginError", false);
    }
//-------------------------------
// User logged in
//-------------------------------
if(get_session("UserID")){
    $tpl->set_var("InputLogin","");
    $tpl->parse("InputLogout",false);
    $db->query("SELECT login,password FROM empleados WHERE id_empleado=". get_session("UserID"));
    $db->next_record();
    $tpl->set_var("login", $db->f("login"));
    $tpl->set_var("PasswordBlock", "");
    $tpl->set_var("sLoginMsg", "El usuario esta registrado en el sistema.");
    $tpl->parse("LoginMessage",false);
    } 
else
  {
    $tpl->set_var("login", "<input type='text' name='login' maxlength='20' value='". $db->f("login")."' size=15 >");
    $tpl->set_var("password", $db->f("password"));
    $tpl->parse("PasswordBlock",false);    
    $tpl->set_var("InputLogout","");
    $tpl->parse("InputLogin",false);

  }
    $tpl->parse("FormLogin", false);

//-------------------------------
// Login Close Event begin
// Login Close Event end
//-------------------------------

//-------------------------------
// Login Show end
//-------------------------------
}
//===============================


?>