<?php
   
namespace model;

//Array of users
class Users{
   
    private $users = array();
    private $userSessionHolder;
    private $selected;
    private $dal;

    public function __construct(sessionHolder $userSessionHolder, SelectedUserDAL $dal) {
		$this->userSessionHolder = $userSessionHolder;
        $this->dal = $dal;
        //Get users in database (selected.User)
		$this->users[] = $this->dal->getSavedUser();
	}
  
    //Set
    //Add a user to database
    public function add(User $user) {
		$this->dal->saveSelection($user);
        $this->users[] = $this->dal->getSavedUser();
	}

    //Get active user
    public function getSelectedUser() {
		return $this->selected;
	}

    //Set active user
    public function setselectUser(User $user) {
		$this->selected = $user;
	}

    //Set
    //Return updated user
    //Update password
    public function updateUser(user $user, $passwordString){
        //Set new hash password
        if (password_needs_rehash($passwordString, PASSWORD_BCRYPT)) {
                $newPassword = password_hash($passwordString, PASSWORD_BCRYPT);
                //Save the new hash in database with this user
                $newUser = $this->dal->updateSelection($user, $newPassword);
                return $newUser;
         }
    }

    //Return user or NULL
    //Set new password
    //Login user
    public function loginUser($nameString, $passwordString) {
		foreach($this->users as $user) {
            
			if ($user->getUsername() === $nameString) {
                //Check/Login with a "Password" string
                if (password_verify($passwordString, $user->getPassword())) {
                        $newUser = $this->updateUser($user, $passwordString);
                        //retrun updated user
                        return $newUser;
                //Login with hash password
                }else if($passwordString === $user->getPassword()){
                        return $user;
                }
            }
		}
        return NULL;
	}

    public function isUserInSystem($username){
        foreach($this->users as $user){
            if($user->getUsername() === $username){
                return TRUE;
            }
            
        }
        return FALSE;
    }
    
    //Set
    //logout user
    public function logout(){
        $this->selected = NULL;
    }
   
    //Set
    //Delete session
    public function unsetSessionUser() {
		$this->userSessionHolder->delete();
	}

    //Bool
    //Is session set?
    public function isSessionSet() {
		return $this->userSessionHolder->set();
	}

    //Set
    //Save session
    public function saveSessionUser() {
		$this->userSessionHolder->save();
	}
}

?>

