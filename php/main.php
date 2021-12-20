<?php
session_start();
$start = microtime(true);
date_default_timezone_set("Europe/Moscow");
$currentTime = date('H:i:s', time());

function checkRectangle($x, $y, $r) {
    return $x <= 0 && $y >= 0 && $x <= -$r/2 && $y <= $r;
}

function checkTriangle($x, $y, $r) {
    return $x >= 0 && $y >= 0 && $y + $x <= $r ;
}

function checkCircle($x, $y, $r) {
    return $x <= 0 && $y <= 0 && sqrt($x * $x + $y * $y) <= $r / 2;
}

function checkHit($x, $y, $r) {
    return checkCircle($x, $y, $r) || checkRectangle($x, $y, $r) || checkTriangle($x, $y, $r);
}

function validateForm($x, $y, $r)
{
    return -5 <= $x and $x <= 3 and -5 <= $y and $y <= 5 and 1 <= $r and $r <= 5;
}

$x = $_GET['x'];
$y = $_GET['y'];
$r = explode(",", $_GET['r']);
$answer = 'No';

foreach ($r as $value) {
    $validation = validateForm($x, $y, $value) ? "Yes" : "No";
    if (checkHit($x, $y, $value)) {
        $answer = 'Yes';
    }

    $executionTime = number_format(microtime(true) - $start, 8, '.', '') . ' ms';

    $result = array($x, $y, $value, $currentTime, $executionTime,  $answer);

    if (!isset($_SESSION['results'])) {
        $_SESSION['results'] = array();
    }
    array_push($_SESSION['results'], $result);

    print_r('<tr><td>' . $x . '</td><td>' . $y . '</td><td>' . $value . '</td><td>' . $currentTime .'</td><td>' . $executionTime .  '</td><td>' .$answer . '</td></tr>');
}
?>