<?php

require_once('vendor/autoload.php');
require_once "ABTest.php";

$tests = array(
    array('user'=>100, 'promotion'=>1),
    array('user'=>1000, 'promotion'=>2),
    array('user'=>5, 'promotion'=>3)
);

foreach($tests as $test) {
    $promotion = $test['promotion'];
    $user = $test['user'];
    echo "Generated design for promotion $promotion for user $user: \n";
    $abTest = new ABTest($promotion, $user);
    echo $abTest->getDesign() . "\n";
}