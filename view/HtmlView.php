<?php


namespace view;


class HtmlView {


	/**
	 * @return String HTML
	 */
	public function getHTMLPage($isLoggedIn, $response, $show) {
        $this->charset = "utf-8";
        $registerLink = "";
        $loggedin = "";
       
        if (isset($_GET['register'])) {
             $q = "?";
             $registerLink = "<a href='$q'>Back to login</a>";                 
        }else{
             $qR = "?register";
             $registerLink = "<a href='$qR'>Register a new user</a>";
        }
           
        if ($isLoggedIn) {
             $loggedin = "<h2>Logged in</h2>";
        }else{
             $loggedin = "<h2>Not logged in</h2>";
        }
          
		return "
            <!DOCTYPE html>
       <html>
         <head>
          <meta charset=\"" . $this->charset . "\">
          <title>Login Example</title>
        </head>
        <body>
         <h1>Assignment 2</h1>
            $registerLink
            $loggedin
         <div>
            $response
            $show
         </div>
          
        </body>
      </html>";
	}
}


?>
