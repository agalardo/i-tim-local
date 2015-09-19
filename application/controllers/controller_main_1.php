<?php

class Controller_Main_1 extends Controller {

    function __construct() {
        //$this->model = new Model_main();
        $this->view = new View();
    }

    function action_index() {
        //$data = $this->model->get_data();
        $this->view->generate('main_view_1.php', 'template_main_view_1.php');
    }

}
?>