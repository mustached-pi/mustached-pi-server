<?php

/*
 * ©2013 The Mustached Pi Project
 */

require 'lib/core.php';

/*
 * Reads the input stream
 */
$request = $_REQUEST;

$house  = \MPi\Entity\House::by('code', $request['sid']);
if (!$house) {
    die('Error');
}

/* Switch on the action.. What to do? */
switch ( $request['event'] ) {
    
    /* Event start */
    case 'start':
        $e = new \MPi\Entity\Event();
        $e->house = $house;
        $e->start = time();
        echo $e->id;
        break;
    
    /* Event finishes */
    case 'stop':
        $e = new \MPi\Entity\Event($request['id']);
        $e->stop = time();
        echo 'Ok';
        break;
    
    /* Upload event movie */
    case 'upload':
        $e = new \MPi\Entity\Event($request['id']);
        $e->upload($_FILES['movie']);
        echo 'Ok';
        break;

    /* Default action */
    default:
        die('Nothing to do');
        break;
}
