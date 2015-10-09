<?php
    
namespace view;

class RegisterView{
    
    /**
	 * These names are used in $_POST
	 * @var string
	 */                         
	private static $messageId = "RegisterView::Message";
	private static $name = "RegisterView::UserName";
	private static $password = "RegisterView::Password";
	private static $passwordRepeat = "RegisterView::PasswordRepeat";
    private static $doRegistration = "RegisterView::Register";
    
    //private $loginView;
    private $model;

    public $message = "";

    //Needs a register model
    public function __construct(\model\users $m){
       // $this->loginView = $loginView;
        $this->model = $m;
    }

    //Returnerar html
    public function response(){
        $this->message = "";

        //if pressed registerbuttom
        if($this->userPressedRegister()){
            if($this->registerUser() == TRUE){
                $this->redirect();
                //Registrera user
            }
        }
        return $this->generateRegisterFormHTML($this->message);
    }

    //messege for registred user
    private function redirect() {
		$_SESSION["registred"] = $this->message;
        $_SESSION["regUser"] = $this->getRequestUserName();
		$actual_link = "http://" . $_SERVER['HTTP_HOST'];
		header("Location: $actual_link");
	}

    //HTML
    //Register form
    private function generateRegisterFormHTML($message) {
		return "<form action='?register' method='post' enctype='multipart/form-data'>
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
					<p id='".self::$messageId."'>$message</p>
                    <label for='".self::$name."'>Username :</label>
					<input type='text' id='".self::$name."' name='".self::$name."' value='".$this->getRequestUserName()."'>
                    <br>
                    <label for='".self::$password."'>Password :</label>
					<input type='password' id='".self::$password."' name='".self::$password."' value>
                    <br>
					<label for='".self::$passwordRepeat."'>Password :</label>
					<input type='password' id='".self::$passwordRepeat."' name='".self::$passwordRepeat."' value>
                    <br>
					<input type='submit' id='submit' name='".self::$doRegistration."' value='Register'>
                    <br>
				</fieldset>
			</form>
		";
    }

    //bool
    public function userPressedRegister() {
		if(isset($_POST[self::$doRegistration])){
		    return TRUE;
		} 
	    return FALSE;
	}
   
    //bool
    //Test user
    private function registerUser(){
        $usernameUnder3 = "Username has too few characters, at least 3 characters.";
        $passwordUnder6 = "Password has too few characters, at least 6 characters.";
        $passwordNoMatch = "Passwords do not match.";
        $usernameExist = "User exists, pick another username.";
        $usernameNoValid = "Username contains invalid characters.";
        $NewUserRegistred = "Registered new user.";

        $username = $this->getUserName();
		$password = $this->getPassword();
	    $passwordRepeat = $this->getPasswordRepeat();

		try {
           
            //Validation user in model class
			 new \model\RegisterUser($username, $password, $passwordRepeat, $this->model);
             return TRUE;
		}  catch (\model\DualExeptException $e) {
            $this->message = $usernameUnder3 . "<br/>" . $passwordUnder6;

         }  catch (\model\NoUsernameCharactersException $e) {
			$this->message = $usernameUnder3;
           
		} catch (\model\NoPasswordCharactersException $e) {
			$this->message = $passwordUnder6;

		}   catch (\model\NoPasswordMatchException $e) {
			$this->message = $passwordNoMatch;
            
		} catch (\model\NoUsernameExeptException $e) {
			$this->message = $usernameExist;

		} catch (\model\NoUsernameValidException $e) {
			$this->message = $usernameNoValid;

		} catch (\model\PassTestUserException $e) {
			$this->message = $NewUserRegistred;
            return TRUE;
		} catch (Exception $e) {
			$this->message = "Unspecified error";
		} 

		return FALSE;
    }

    //retrun strin 
    private function getRequestUserName() {
		if (isset($_POST[self::$name])){
            //Valedering on retrun value username in field
            //Add more regex or another...
            return strip_tags($_POST[self::$name]);
        }
		return "";
	}

    private function getUserName() {
		if (isset($_POST[self::$name])){
		    return trim($_POST[self::$name]);
		}
		return "";
	}

	private function getPassword() {
		if (isset($_POST[self::$password]))
			return trim($_POST[self::$password]);
		return "";
	}

    private function getPasswordRepeat() {
		if (isset($_POST[self::$passwordRepeat]))
			return trim($_POST[self::$passwordRepeat]);
		return "";
	}
}


?>


