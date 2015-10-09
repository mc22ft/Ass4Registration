<?php
    
namespace controller;

class MasterController{
    
    private $users;
    private $htmlView;
    private $navigationView;
    private $dateTimeView;

    public function __construct(){

        $sessionHolder = new \model\SessionHolder("userSessionHolder");
        $dal = new \model\SelectedUserDAL();
        $this->users = new \model\Users($sessionHolder, $dal);
        $this->htmlView = new \view\HtmlView();

        $this->navigationView = new \view\NavigationView();
        $this->dateTimeView = new \view\DateTimeView();
    }

    public function handleInput(){

        //Login form
        if($this->navigationView->doLogin()){

            //new logincontroller
            $loginController = new \controller\LoginController($this->users, $this->htmlView, $this->dateTimeView);
            
            //input
            $loginController->doLoginControl();

            //output - get loginView
            $this->view = $loginController->getView();
        }else{
            //Register form
            if($this->navigationView->doRegister()){
                
                $userController = new \controller\UserController($this->users, $this->htmlView, $this->dateTimeView);
            
                //input
                $userController->doRegisterControl();

                //output - get loginView
                $this->view = $userController->getView();
            }
        }
    }

    //Return output index
    public function generateOutput() {
		return $this->view;
	}




}
?>
