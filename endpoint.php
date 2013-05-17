<?php

/*
 * ©2013 The Mustached Pi Project
 */

require 'lib/core.php';

/*
 * Reads the input stream
 */
$request = file_get_contents("php://input");
$request = json_decode($request);

$house  = \MPi\Entity\House::by('code', $request->sid);
if (!$house) {
    echo json_encode([
        'house' =>  false
    ]);
    die();
}

if ( $request && isset($request->ports) ) {
    foreach ( $request->ports as $port => $input ) {
        $i = new \MPi\Entity\Input();
        $i->house = $house->id;
        $i->num   = (int) $port;
        $i->value = (int) $input;
        $i->timestamp = time();
    }
}
 
echo json_encode([
    'house'     => $house->toJSON(),
    'ports'     => $house->instancesToJSON(),
    'request'   => $request
], JSON_FORCE_OBJECT | JSON_PRETTY_PRINT);