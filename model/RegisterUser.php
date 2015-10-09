<?php

//This class creates a User if validation passes

namespace model;

class NoUsernameCharactersException extends \Exception {};
class NoPasswordCharactersException extends \Exception {};
class NoUsernameValidException extends \Exception {};
class DualExeptException extends \Exception {};
class NoUsernameExeptException extends \Exception {};
class NoPasswordMatchException extends \Exception {};
class PassTestUserException extends \Exception {};

class RegisterUser {

    public function __construct($username, $password, $passwordRepeat, $usersModel) {
        
        if (mb_strlen($username) < 3 && mb_strlen($password) < 6){
			throw new DualExeptException();
            }

        if (mb_strlen($username) < 3){
			throw new NoUsernameCharactersException();
            }

		if (mb_strlen($password) < 6){
			throw new NoPasswordCharactersException();
            }

        if($password != $passwordRepeat){
                throw new NoPasswordMatchException();
            }

        if ($usersModel->isUserInSystem($username)) { 
			    throw new NoUsernameExeptException();
		    }
            
        if (!is_string($username) || $username !== strip_tags($username)) { 
			    throw new NoUsernameValidException();
		    }

            //ToDo To controller!!

            //create new user (to add in database)
            $user = new User($username, $password);
 
            //TODO Add to database
			$usersModel->add($user);
            //Update password to hash password in db
            $usersModel->updateUser($user, $password);

             //Pass all tests
             if ($usersModel->isUserInSystem($username)) { 
			    throw new PassTestUserException();
		     }
	}
   
}

