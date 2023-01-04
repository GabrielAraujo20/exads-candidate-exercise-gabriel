<?php

for($n = 1; $n <= 100; $n++) {
    $multiples = array();
    for($i=2;$i<$n;$i++) {
        if($n%$i === 0)
            $multiples[] = $i;
    }
    $multiplesString = count($multiples) === 0 ? "PRIME" : implode(",", $multiples);
    echo "$n"."[$multiplesString]";
}