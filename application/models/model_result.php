<?php

class Model_result extends Model {

    public function get_data() {
        $xml = urldecode($_POST['resultEmail']);
        $xml = str_replace("&", "&amp;", $xml);
        return $xml;
    }

}

?>
