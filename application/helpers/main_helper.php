<?php

const ADMIN_USER_TYPE       = 'admin';
const CUSTOMER_USER_TYPE    = 'user';

function getCurrentUserType(){
    if (isset($_SESSION['admin'])){
        return 'admin';
    }
    if (isset($_SESSION['user'])){
        return 'user';
    }
    return null;
}

function isCurrentUserCustomer(){
    return getCurrentUserType() == 'user';
}

function isCurrentUserAdmin(){
    return getCurrentUserType() == 'admin';
}

function isCurrentUserNotLoggedIn(){
    return getCurrentUserType() == null;
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

function getFullSizePages(){
    return array(
        'planningActivity'
    );
}

function formatPrice($number){
    return number_format((float)$number, 2, '.', '');
}