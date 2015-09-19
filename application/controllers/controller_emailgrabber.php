<?php

class Controller_Emailgrabber extends Controller {

    function __construct() {
        $this->view = new View();
    }

    function action_index() {
        
        $this->view->generate('view_emailgrabber.php', 'template_grabber.php');
    }

}
?>
