<?php

/*
 * ©2013 The Mustached Pi Project
 */

$house  = $_POST['house'];
$house  = new \MPi\Entity\House($house);

$type   = (int) $_POST['inputType'];
$num    = (int) $_POST['inputNum'];

$p = new \MPi\Entity\Port();
$p->house   = $house->id;
$p->num     = $num;
$p->type    = $type;
$p->name    = 'PORT';
$p->timestamp = time();

if ( $type == INPUT ) {
    $x = new \MPi\Entity\Input();
} else {
    $x = new \MPi\Entity\Output();
}
$x->house   = $house->id;
$x->num     = $num;
$x->value   = 0;
$x->timestamp = time();

redirect('dash&id=' . $house->id);