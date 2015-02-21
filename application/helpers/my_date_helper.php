<?php

/**
 * 	Sets and returns a default date
 *
 */
function get_default_start_date()
{
    return '20-08-2013';
}

/** 	SQL Compatible Formate of time
 * 	@author : 	"Shihabu Rahman K" <shihab@levoirsolutions.com>
 * 	@params : 	$pre 	-> prefix to be prepented with time, may a white space or anything like.
  $time 	-> Time in seconds since the Unix Epoch (January 1 1970 00:00:00 GMT). if no time current time will be returned.
 * 	@return : 	Formated time as string.
 * 	@access public
 */
function getSQLtime($pre = '', $time = '')
{
    $time = $time ? $time : time();
    #	The timezone of 'localhost' is "(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi". 
    #	So we have to add 5 hours and 30 minuts to the current time to get system time.
    date_default_timezone_set('UTC');
    $pastHours = 5 * 60 * 60;  # 5 hours Than UTC.
    $pastMinuts = 30 * 60;  # 30  Minuts Than UTC.
    $time = $time + $pastHours + $pastMinuts;
    return $pre . date('H:i:s', $time);
}

/** 	SQL Compatible Formate of Date
 * 	@author : 	"Shihabu Rahman K" <shihab@levoirsolutions.com>
 * 	@params : 	$date -> Date should be formated. If no $date current date should be taken.
 * 	@return : 	Formated date as string.
 * 	@access public
 */
function getSqlDate($date = '')
{
    if ($date)
        return date('Y-m-d', strtotime($date));
    return date('Y-m-d');
}

/** 	SQL Compatible Formate of DATETIME
 * 	@author : 	"Shihabu Rahman K" <shihab@levoirsolutions.com>
 * 	@params : 	$date 	-> Date should be formated. If no $date current date should be taken.
  $time 	-> Time in seconds since the Unix Epoch (January 1 1970 00:00:00 GMT). if no time current time will be returned.
 * 	@return : 	Formated date as string.
 * 	@access public
 */
function getSqlDateTime($date = '', $time = '')
{
    $date = getSqlDate($date);
    $time = getSQLtime($time);

    return "$date $time";
}

/** 	Formate a Date
 * 	@author : 	"Shihabu Rahman K" <shihab@levoirsolutions.com>
 * 	@params : 	$date -> Date should be formated.
 *      @params : 	$default -> BOOLEAN : if its value is TRUE and the value of $date is NULL, return the current date as default.
 *      @params : 	$indux -> Indux of defined format.
 * 	@return : 	Formated date as string.
 * 	@access public
 */
function formatDate($date = '', $default = true, $indux = 0,$time=FALSE)
{
    $format[] = $time ? 'd/m/Y h:i A' : 'd/m/Y';
    $format[] = $time ? 'd-m-Y h:i A' : 'd-m-Y';

    $date = $date ? date($format[$indux], strtotime($date)) : ($default ? date($format[$indux]) : '');
    return $date;
}

function getMonthOptions()
{
    $month['01'] = 'Jan';
    $month['02'] = 'Feb';
    $month['03'] = 'Mar';
    $month['04'] = 'Apr';
    $month['05'] = 'May';
    $month['06'] = 'Jun';
    $month['07'] = 'Jul';
    $month['08'] = 'Aug';
    $month['09'] = 'Sep';
    $month['10'] = 'Oct';
    $month['11'] = 'Nov';
    $month['12'] = 'Dec';
    return $month;
}

function getYearOptions($from, $to)
{
    for($from; $from <= $to; $from++)
        $year[$from] = $from;
    return $year;
}

?>