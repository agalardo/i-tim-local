<?php
preg_match_all('/<h3 class=["]?r["]?><a href="http:\/\/([^>]+?)"[^>]+?>/', $response, $matches);  //URL of site
preg_match_all('/<td><a class=["]?fl["]? href="([^>]+?)">/', $response, $matches_nav);  //URL of page
preg_match_all('/<td><a class=["]?fl["]? href="[^>]+?"><span [^>]+?><\/span>(\d{1,2})<\/a><\/td>/', $response, $page_nav);  //Number of page
?>