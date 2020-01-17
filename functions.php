<?php

function convert($time, $timezone) {
    // timezone by php friendly values
    $date = new DateTime(date('Y-m-d ') . $time . ':00', new DateTimeZone('UTC'));
    $date->setTimezone(new DateTimeZone($timezone));
    $time= $date->format('H:i');
    return $time;
}
