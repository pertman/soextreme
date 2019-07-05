<?php

function verifyReferer(){

//    @TODO add Mobile Refferer
    if (!isset($_SERVER['HTTP_REFERER'])){
        return false;
    }

    $address = 'http://' . $_SERVER['SERVER_NAME'];

    if (strpos($_SERVER['HTTP_REFERER'], $address) !== 0) {
        return false;
    }

    return true;
}

function verifyCSRF(){
    $headers = apache_request_headers();

    if (!isset($headers['Csrftoken'])) {
        return false;
    }

    if ($headers['Csrftoken'] !== $_SESSION['csrf_token']) {
        return false;
    }

    return true;
}
