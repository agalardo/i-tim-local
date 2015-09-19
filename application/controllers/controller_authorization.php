<?php

class Controller_authorization extends Controller {

    function __construct() {
        $this->model = new Model_authorization();
        $this->view = new View();
    }

    function action_index() {
        $data = $this->model->get_data();
        $this->view->generate('authorization_view.php', 'template_main_view.php');
    }

}
?>