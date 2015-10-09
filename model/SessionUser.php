<?php

namespace model;

class SessionUser {

    private $ip; //Ip number on users computer
    private $browser; //Browser that user open the page in

    public function __construct($ip, $browser) {
			   $this->ip = $ip;
               $this->browser = $browser;
	}
    
    public function getIp(){
        return $this->ip;
    }

    public function getBrowser(){
        return $this->browser;
    }
}

?>
