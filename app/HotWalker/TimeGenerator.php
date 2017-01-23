<?php namespace App\HotWalker;

use DateTime;
use DateTimeZone;

/**
 * Created by PhpStorm.
 * User: stitch-saleh
 * Date: 5/25/16
 * Time: 7:16 PM
 */
class TimeGenerator
{
    public static function generate($last_bomb_time = null, $max_bombs_reached_for_week = false) {
        $now = new DateTime();
        $now->setTimezone(new DateTimeZone('America/Los_Angeles'));
        $now_string = print_r($now, true);
        $min_date_index = date('w', strtotime($now_string));

        $friday_index = 5;

        if(is_null($last_bomb_time)) {
            $day_of_week = date('l', strtotime($now_string));
            $min_date = $now;
            switch($day_of_week) {
                case 'Saturday':
                    $min_date = $now->add(new \DateInterval('P2D'));
                    break;
                case 'Sunday':
                    $min_date = $now->add(new \DateInterval('P1D'));
                    break;
                default:
            }


            $min_date_string = print_r($min_date, true);
            $min_date_index = date('w', strtotime($min_date_string));
            dd($min_date_index);
            $end_of_week_offset = $friday_index - $min_date_index;
            dd($end_of_week_offset);
            $max_date = $min_date->add(new \DateInterval("P{$end_of_week_offset}D"));
            $max_date_string = print_r($max_date, true);
            dd($max_date);
        }

        $min_epoch = strtotime($min_date_string);
        $max_epoch = strtotime($max_date_string);

        $rand_epoch = rand($min_epoch, $max_epoch);

        $rand_date_string = print_r(date('Y-m-d', $rand_epoch), true);

        // Get Hour
        $hours_min = 9;
        $hours_max = 17;

        $random_hour = rand($hours_min, $hours_max);

        // Get Min
        $random_min = rand(0, 5);

        // Date format "2016-07-07 10:20:00"
        $rand_date_string .= " {$random_hour}:{$random_min}0:00";
        $random_date = new DateTime($rand_date_string);
        dd($random_date);

        return $date;
    }
}