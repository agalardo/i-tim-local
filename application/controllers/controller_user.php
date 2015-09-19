<?php

class Controller_user extends Controller {

    public $authentication;
    public $auth;
    
    function __construct() {
        include_once "libraries/authentication.class.php";
        $this->authentication = new authentication($_POST['nickname'], $_POST['userpassword']);
        $this->auth = $this->authentication->do_authentication();
        $this->model = new Model_user();
        $this->view = new View();
    }

    function action_index() {
        
        if ($this->auth == 1) {
            //аутентификация не прошла
            $this->view->generate('view_authentication.php', 'template_main_view.php');
        } else {
            $data = $this->model->get_data();
            $this->view->generate('view_user.php', 'template_view.php', $data);
        }
    }

}
