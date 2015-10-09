<?php
//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On'); 

//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once("model/User.php");
require_once("model/Users.php");
require_once("model/SelectedUserDAL.php");
require_once("controller/LoginController.php");
require_once("model/SessionHolder.php");
require_once("model/SessionUser.php");
require_once("model/RegisterUser.php");
require_once("view/RegisterView.php");
require_once("controller/UserController.php");
require_once("controller/MasterController.php");
require_once("view/HtmlView.php");
require_once("view/NavigationView.php");

session_start();

$m = new \controller\MasterController();
$m->handleInput();
//html view
$view = $m->generateOutput(); //from mastercontroller
echo $view; 