<?php

namespace view;


class NavigationView {

	/**
	 * Used to build URLs to a certain product
	 * @var string
	 */
	private static $doLogin = "";
    private static $doRegister = "register";

	public function doLogin() {
        //snabb fulhack :) 
        return isset($_GET[self::$doRegister]) == false;
           
	}

    public function doRegister() {
        //var_dump(isset($_GET[self::$doRegister]));
		return isset($_GET[self::$doRegister]) == true;
	}
}