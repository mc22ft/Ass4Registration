<?php

namespace view;

class LoginView {

    private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

	private $users;
   
    private $loginOrLoggedout = false;
    private $userCookieLogin = FALSE;

	public function __construct(\model\Users $users) {
		$this->users = $users;
       
	}

    //Set
    public function loginHasFailOrSucceeded($trueFalse){
        if($trueFalse){
            $this->loginOrLoggedout = TRUE;
        }
        else{
            $this->loginOrLoggedout = FALSE;
        }
    }

    //set
    public function userCookieLogin() {
		 $this->userCookieLogin = TRUE;
	}
    
    /**
	 * @return boolean true if user did try to login
	 */
	public function userPressedLogin() {
		if(isset($_POST[self::$login])){
		    return TRUE;
		} 
	    return FALSE;
	}

	/**
	 * Accessor method for logout events
	 * 
	 * @return boolean true if user tried to logout
	 */
	public function userPressedLogout() {
        if(isset($_POST[self::$logout])){
		    return TRUE;
		} 
	    return FALSE;	
	}

    //Check if session is set and if there is a user logged in
    public function isLoggedIn(){
        if($this->users->isSessionSet()){
             return TRUE;
        }else{
            return FALSE;
        }
    }

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
        $message = "";
       
        //Fulhack
        //message if new user registred
        if(isset($_SESSION["registred"])){
            $message = $_SESSION["registred"];
            unset($_SESSION["registred"]);
        }

        //bool
        //If logout with logout bottom
        if($this->doByeByeLogout()){
            return $this->generateLoginFormHTML("Bye bye!");
        }

        //if session is set
        if($this->loginOrLoggedout === TRUE){
            $message = "Welcome";
            //Set
            //If keep buttom is set - set cookies
            if(isset($_POST[self::$keep])){
                $this->setCookie();
                $message = "Welcome and you will be remembered";
            }
            //Login form
            return $this->generateLogoutButtonHTML($message);
        }

        //Bool
        //Test before login - sessionlogoin
        if($this->users->isSessionSet()){
            //THIS MAKE MESSAGE FOR COOKIE LOGIN!!!
            if($this->userCookieLogin){
                return $this->generateLogoutButtonHTML("Welcome back with cookie");
            }
            return $this->generateLogoutButtonHTML("");
        }

        //Bool
        //Check cookies and if cookies is manupilated
        if($this->isCookieSet()){
            if($this->cookieLogin()){ 
            }else{
                return $this->generateLoginFormHTML("Wrong information in cookies");
            }
        }

        //Login buttom pressed
        //Field empty test for username and password                      -----   POST LOGIN   -----
        if($this->userPressedLogin()){
            if(empty($_POST[self::$name])){
                $message = "Username is missing";
            }else{
                  if(empty($_POST[self::$password])){
                      $message = "Password is missing";
                  }else{  
                         $message = "Wrong name or password";
                   }
             }
       }

       return $this->generateLoginFormHTML($message);
	}


	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
        return '
			<form method="post"> 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->getRequestUserName() . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}
	
    //Get
	//Return request username variable to loginform
	private function getRequestUserName() {
        if(isset($_POST[self::$name])){
            return $_POST[self::$name]; 
        }
        else{
            if(isset($_SESSION["regUser"])){
            return $_SESSION["regUser"];
            unset($_SESSION["regUser"]);
        }
            return "";
        }
	}

    //Bool
    //Set/Unset session
    private function doByeByeLogout(){
        if(isset($_SESSION[self::$messageId])){
                $message = "" . $_SESSION[self::$messageId];
                unset($_SESSION[self::$messageId]);
                return FALSE;
        }else if(isset($_POST[self::$logout])){
                $this->unsetCookies();
                $message = "Bye bye!";
                $_SESSION[self::$messageId] = $message;
                return TRUE;
        }
    }

    //Set
    private function setCookie(){
         $selectedUser = $this->users->getSelectedUser();
         setcookie(self::$cookieName, $selectedUser->getUsername(), time() + 3600);
         setcookie(self::$cookiePassword, $selectedUser->getPassword(), time() + 3600);
    }

    //Bool
    public function isCookieSet(){
       if(isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword])){   // only if it is set
          return TRUE;
       }
        return FALSE;
    }

    //Bool
    public function cookieLogin(){
          if(isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword])){   // only if it is set
                  $user = $this->users->loginUser($_COOKIE[self::$cookieName], $_COOKIE[self::$cookiePassword]);
               if($user != NULL) {
                    $this->users->setselectUser($user);
                    $this->users->saveSessionUser();
                    //NOT manipulated cookie
                    $this->setCookie();
                    return TRUE;
               }else{
                    //Manipulated cookie
                    return FALSE;
               }
          }
        return FALSE;
    }

    //Unset
    public function unsetCookies(){
       
        //Delete cookie name and password
        if(isset($_COOKIE[self::$cookieName])){   // only if it is set
            setcookie(self::$cookieName, "", time() - 3600);
        }
        if(isset($_COOKIE[self::$cookiePassword])){   // only if it is set
            setcookie(self::$cookiePassword, "", time() - 3600); //TIME?
        }
    }

    //Get
    public function getViewUser() {
		return new \model\User($this->getUserName(), 
									$this->getPassword());
	}

    private function getUserName() {
		if (isset($_POST[self::$name]))
			return trim($_POST[self::$name]);

		if (isset($_COOKIE[self::$cookieName]))
			return trim($_COOKIE[self::$cookieName]);
		return "";
	}

	private function getPassword() {
		if (isset($_POST[self::$password]))
			return trim($_POST[self::$password]);

            if (isset($_COOKIE[self::$cookiePassword]))
			return $_COOKIE[self::$cookiePassword];
		return "";
	}
}