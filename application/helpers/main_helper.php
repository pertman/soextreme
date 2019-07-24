<?php

const ADMIN_USER_TYPE       = 'admin';
const CUSTOMER_USER_TYPE    = 'user';

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

function getCurrentUserType(){
    if (isset($_SESSION['admin'])){
        return 'admin';
    }
    if (isset($_SESSION['user'])){
        return 'user';
    }
    return null;
}

function getAdminUserType(){
    return 'admin';
}

function getCustomerUserType(){
    return 'user';
}

function getActivitiesStatusMapping(){
    return array(
        'active' => 'Active',
        'unavailable' => 'Non Disponible',
        'private' => 'Privée',
    );
}

function getActivitiesStatusColorMapping(){
    return array(
        'active' => 'green',
        'unavailable' => 'orange',
        'private' => 'red',
    );
}


function getDurationValueFromMinute($minutes){
    if ($minutes >= 60){
        $hours = round($minutes / 60, 0);
        $minutes = $minutes - ($hours * 60);
        
        if (($minutes / 10) < 1){
            $minutes = "0".$minutes;
        }

        return $hours .'h'. $minutes;
    }
    else{
        return $minutes .  'min';
    }
}