<?php
    
namespace model;

class SelectedUserDAL {

    //"Database file"
	private static $fileName = "selectedUser.User";

	//Get all users
	public function getSavedUser() {
		$fileContent = @file_get_contents(self::$fileName);
		if ($fileContent !== FALSE){
			return unserialize($fileContent);
		}
		return null;
	}

    //Set
    //Return updated user
    public function updateSelection(User $toBeUpdatedUser, $updatedUserPassword) {
        $arrUsers[] = $this->getSavedUser();
        foreach($arrUsers as $user) {
             if($user->getUsername() === $toBeUpdatedUser->getUsername()){
                //Set new password
                $user->setPassword($updatedUserPassword);
                //Save update user
                $this->saveSelection($user);
                return $user;
            }
        }
	}

    //Set user in "database"
	public function saveSelection(User $toBeSaved) {
		$content = serialize($toBeSaved);
		file_put_contents(self::$fileName, $content);
	}
}

?>