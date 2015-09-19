<?php

class Model_authorization extends Model {

    public function get_data() {
        session_start();
        $_SESSION["redirect"] = 1;
    }

}

?>