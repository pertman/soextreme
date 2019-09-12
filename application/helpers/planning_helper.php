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