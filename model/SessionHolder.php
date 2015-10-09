<?php

namespace model;

class SessionHolder{

    private $sessionHolderId;
    private $sessObj;
    
    public function __construct($sessionHolderId) {
		$this->sessionHolderId = $sessionHolderId;
        $this->sessObj = new \model\SessionUser($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
		//Make sure we have a session
		assert(isset($_SESSION));
	}

    //Set object in session
	public function save() {
		$_SESSION[$this->sessionHolderId] = $this->sessObj;
	}

    //Set
    //Delete session
    public function delete() {
		unset($_SESSION[$this->sessionHolderId]);
	}

    //Bool (is session set?)
    //Compare this user, if ip number and browser is the same
    public function set() { 
        if (isset($_SESSION[$this->sessionHolderId]) && isset($this->sessObj)) {
            if($_SESSION[$this->sessionHolderId]->getIp() === $this->sessObj->getIp() &&
                    $_SESSION[$this->sessionHolderId]->getBrowser() === $this->sessObj->getBrowser()){
                return TRUE;
            }
		}
        return FALSE;
	}
}

?>

