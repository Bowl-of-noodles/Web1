<?php
require_once("jsonEncode.php");

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

function validateTimezone($timezoneOffset) {
    return isset($timezoneOffset) && is_numeric($timezoneOffset) && $timezoneOffset % 60 === 0;
}

function getResultArray($x, $y, $r, $timezone) {
    $results = array();

    foreach ($r as $value) {
        $isValid = validateForm($x, $y, $value);
        $isHit = checkHit($x, $y, $value);
        $currentTime = date('H:i', time() - $timezone * 60);
        $executionTime = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 7);

        array_push($results, array(
            "validate" => $isValid,
            "x" => $x,
            "y" => $y,
            "r" => $value,
            "currentTime" => $currentTime,
            "execTime" => $executionTime,
            "isHit" => $isHit
        ));
    }

    return $results;
}

function generateTableWithRows($wholeTable, $results) {
    $html = $wholeTable == 'true'? '<table id="result_table"><tr class="first">
        <th class="coords-col">X</th>
        <th class="coords-col">Y</th>
        <th class="coords-col">R</th>
        <th class="time-col">Время запроса</th>
        <th class="time-col">Время исполнения</th>
        <th class="hitres-col">Попадание</th>
    </tr>': '';

    foreach ($results as $elem)
        $html .= generateRow($elem);

    if ($wholeTable == 'true') $html .= '</table>';
    return $html;
}

function generateRow($elem) {
    $isHit = $elem['isHit'] ? 'Да': 'Нет';
    $elemHtml = $elem["isHit"]? '<tr class="hit-yes">' : '<tr class="hit-no">';
    $elemHtml .= '<td>' . $elem['x'] . '</td>';
    $elemHtml .= '<td>' . $elem['y'] . '</td>';
    $elemHtml .= '<td>' . $elem['r'] . '</td>';
    $elemHtml .= '<td>' . $elem['currentTime'] . '</td>';
    $elemHtml .= '<td>' . $elem['execTime'] . '</td>';
    $elemHtml .= '<td>' . $isHit . '</td>';
    $elemHtml .= '</tr>';

    return $elemHtml;
}


$x = $_GET['x'];
$y = $_GET['y'];
$r =  explode(",", $_GET['r']);
$dataType = $_GET['dataType'];
$wholeTable = $_GET['wholeTable'];

if (!isset($wholeTable)) $wholeTable = true;

$timezone = $_GET['timezone'];

$results = getResultArray($x, $y, $r, $timezone);

if($dataType == 'html')
    echo generateTableWithRows($wholeTable, $results);
