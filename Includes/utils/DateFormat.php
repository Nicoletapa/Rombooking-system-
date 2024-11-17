<?php
function formatDate($dateString, $format = 'd-m-Y') {
    $date = new DateTime($dateString);
    return $date->format($format);
}
?>