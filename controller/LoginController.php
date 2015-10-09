<?php

namespace controller;

class LoginController{

    private $users;
    private $loginView;
    private $dateTimeView;
    private $htmlView;
    private $logView = null;

    public function __construct(\model\users $users, \view\HtmlView $htmlView, \view\dateTimeView $dateTimeView){
        $this->users = $users;
        $this->loginView = new \view\loginView($users);
        $this->dateTimeView = $dateTimeView;
        $this->htmlView = $htmlView;

       }
       public function doLoginControl(){

           if($this->users->isSessionSet()){

                //Check if LOGOUT button is pressed
               if($this->loginView->userPressedLogout()){
                   //true 
                   //logut user model and clear session
                    $this->users->unsetSessionUser();
                    $this->users->logout();
                    //clear cookis in view
                    $this->loginView->unsetCookies();
               }

           }
           else{

               //Check if LOGIN button is pressed
               if($this->loginView->userPressedLogin()){
                   //true 
                   //gets a user from view
                   $user = $this->loginView->getViewUser();
                   
                   $selectedUser = $this->users->loginUser($user->getUsername(), $user->getPassword());
                    if($selectedUser != NULL){
                        //Set user in model
                        $this->users->setselectUser($selectedUser);
                        //Set session in model
                        $this->users->saveSessionUser();
                        //Set loginHasFailOrSucceeded
                        $this->loginView->loginHasFailOrSucceeded(TRUE);
                   }else{
                        $this->loginView->loginHasFailOrSucceeded(FALSE);
                   }

               }else {
                   //Do cookie login if its set
                   $this->loginView->cookieLogin();
                   //Set to tru in view
                   $this->loginView->userCookieLogin();
               }
           }
           //initiate view
	       $this->logView = $this->htmlView->getHTMLPage($this->loginView->isLoggedIn(),  $this->loginView->response(), $this->dateTimeView->show());
       }

       /**
	 * 
	 * @return View
	 */
	public function getView() {

		if ($this->logView != null) {
			return  $this->logView;
		} else {
			return $this->logView;
		}
	}



    //Bool
    //Login
    //public function doLoginUser($username, $password){
    //    //Get user or NULL
    //    $selected = $this->users->loginUser($username, $password);
    //    if($selected != NULL){
    //        //Set user in model
    //        $this->users->setselectUser($selected);
    //        //Set session in model
    //        $this->users->saveSessionUser();
    //        return TRUE;
    //    }
    //    return FALSE;
    //}

    ////Set
    ////Logout
    //public function doLogout(){
    //    $this->users->unsetSessionUser();
    //    $this->users->logout();
    //}
}
?>
