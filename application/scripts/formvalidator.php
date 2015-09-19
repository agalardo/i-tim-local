<?php

header("Content-Type: text/plain");
header("Cache-Control: no-cache");
include_once "../../libraries/connect_db.class.php";

$user_tablename = "users";

function in_use_nickname($nickname) {
    global $user_tablename;
    $query = "SELECT nickname FROM $user_tablename WHERE nickname='$nickname'";
    $result = mysql_query($query);
    if (!mysql_num_rows($result)) {
        return 0;
    } else {
        return 1;
    }
}

function in_use_email($email) {
    global $user_tablename;
    $query = "SELECT email FROM $user_tablename WHERE email='$email'";
    $result = mysql_query($query);
    if (!mysql_num_rows($result)) {
        return 0;
    } else {
        return 1;
    }
}

if (isset($_GET["nickname"]) || isset($_GET["email"])) {
    $ObjDb = new connect_db();
    $ObjDb->db_connect();

    if (isset($_GET["nickname"])) {
        $searchTerm = strip_tags($_GET["nickname"]);
        if (in_use_nickname($searchTerm))
            $result = true;
    }

    if (isset($_GET["email"])) {
        $searchTerm = strip_tags($_GET["email"]);
        if (in_use_email($searchTerm))
            $result = true;
    }

    $strResult = ($result) ? "not available" : "available";

    $ObjDb->db_close();

    echo $strResult;
}
else {
    echo "PHP is working correctly. Congratulations!";
}
?>