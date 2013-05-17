<?php

/*
 * ©2013 The Mustached Pi Project
 */

$house  =   $_GET['house'];
$num    =   $_GET['num'];

$last   = \MPi\Entity\Output::lastValue($house, $num);
$value  = (bool) $last->value;
$value  = (int) !$value;

$p = new \MPi\Entity\Output();
$p->house = $house;
$p->num   = $num;
$p->value = $value;
$p->timestamp = time();

redirect('dash&id='. $house);