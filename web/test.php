<?php

$rider_name = 'Travis Redgar';
$rider_names=explode(' ',$rider_name);
if (count($rider_names) >= 2){
    $rider_names[1] = strtoupper($rider_names[1][0]) . '.';
}
$rider_name=implode(' ', $rider_names);

echo $rider_name;