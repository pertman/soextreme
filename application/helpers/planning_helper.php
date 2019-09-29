<?php

function addMinutesToTime($time, $minutes) {
    $time = DateTime::createFromFormat( 'H:i:s', $time );
    $time->add(new DateInterval( 'PT' . (integer) $minutes . 'M' ));
    $newTime = $time->format( 'H:i:s' );

    return $newTime;
}

function formatDateFromFrToUs($date){
    $dateArray = explode('/', $date);
    return $dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0];
}

function formatDateFromUsToFr($date){
    $dateArray = explode('-', $date);
    return $dateArray[2].'/'.$dateArray[1].'/'.$dateArray[0];
}

function formatTimeSlot($timeSlot){
    $timeSlotArray = explode('-', $timeSlot);
    return 'de ' . $timeSlotArray[0] . 'h à '. $timeSlotArray[1] . "h";
}

function formatDateAndTime($dateAndTime){
    
    $dateTime = new DateTime($dateAndTime);
    $dateTime = $dateTime->modify('+ 2 hour');
    $date     = $dateTime->format('d/m/Y');
    $hour     = $dateTime->format('H:i');

    return 'Le '. $date . ' à '. $hour . 'h';
}

function getTimeSlotStartHour($timeSlot){
    $timeSlotArray = explode('-', $timeSlot);
    return $timeSlotArray[0];
}