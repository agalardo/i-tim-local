<?php

class Controller_Result extends Controller {

    function __construct() {
        $this->model = new Model_result();
        $this->view = new View();
    }

    function action_index() {
        $data = $this->model->get_data();
        $this->view->generate('view_result.php', 'template_result.php', $data);
    }

}
?>
