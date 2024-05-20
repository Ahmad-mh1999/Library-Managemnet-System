<?php

namespace App\Helpers;

use DateTime;

function formateDate($dateString, $format = 'Y-m-d')
{
    $date = new DateTime($dateString);
    return $date->format($format);
}
