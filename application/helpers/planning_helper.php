<?php

function getSessionsBetweenHours($startHour, $endHour, $duration){
    $slots  = array();
    $i      = 0;

    $time = $startHour;

    while ($time < $endHour){
        $slots[$i]['start'] = $time;
        $time = addMinutesToTime($time, $duration);
        $slots[$i]['end'] = $time;

        if ($time > $endHour){
            unset($slots[$i]);
            break;
        }

        $i++;
    }

    return $slots;
}

function addMinutesToTime($time, $minutes) {
    $time = DateTime::createFromFormat( 'H:i:s', $time );
    $time->add(new DateInterval( 'PT' . (integer) $minutes . 'M' ));
    $newTime = $time->format( 'H:i:s' );

    return $newTime;
}