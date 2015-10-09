<?php
namespace controller;

class UserController{
    
    private $users;
    private $registerView;
    private $dateTimeView;
    private $htmlView;
    private $logView = null;

    public function __construct(\model\users $users, \view\HtmlView $htmlView, \view\dateTimeView $dateTimeView){
        $this->users = $users;
        $this->registerView = new \view\registerView($users);
        $this->dateTimeView = $dateTimeView;
        $this->htmlView = $htmlView;
       }
	   
     public function doRegisterControl(){
         
         if($this->registerView->userPressedRegister()){

        //Hey!
        //Det blev lite stressigt här mot slutet. Men har jobbat extra mycket på att 
        //få controllerna bra. Men jag han inte med denna :(
        //Nedan vissar lite hur jag tänkte iaf...


   //        //gets new user (to add in database)
   //        $user = $this->registerView->getViewUser();

   //         //TODO Add to database
			  //$this->users->add($user);
   //         //Update password to hash password in db
   //         $this->users->updateUser($user, $user->getPassword());

         }

         //initiate view
	     $this->logView = $this->htmlView->getHTMLPage(false,  $this->registerView->response(), $this->dateTimeView->show());
           
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

}

?>
