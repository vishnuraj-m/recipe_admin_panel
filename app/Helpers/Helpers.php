<?php

class Helpers
{

    static function convertToHoursMins($minutes, $format = '%2d:%02d')
    {
        if ($minutes < 1) {
            return;
        }

        if ($minutes >= 60) {
            $hours = floor($minutes / 60);
            $minutes = ($minutes % 60);
            return $hours .' h '. $minutes . ' min';
        } else {
            return $minutes. ' min';
        }
    }
    
}
