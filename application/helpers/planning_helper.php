<?php

function getSessionsBetweenHours($startHour, $endHour, $duration, $actParticipantNb){
    $slots  = array();
    $i      = 0;

    $time = $startHour;

    while ($time < $endHour){
        $slots[$i]['start']         = $time;
        $time                       = addMinutesToTime($time, $duration);
        $slots[$i]['end']           = $time;
        $slots[$i]['participantNb'] = $actParticipantNb;
//      @TODO check if reservation for update participant Nb

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