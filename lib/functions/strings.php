<?php

/*
 * ©2013 The Mustached Pi Project
 */

/*
 * Normalize and lowercase a string
 */
function toLowercase($input) {
    $input = trim($input);
    $input = strtolower($input);
    return $input;
}

function toUppercase($input) {
    $input = toLowercase($input);
    $input = strtoupper($input);
    return $input;
}

function toNamestring($input) {
    $input = toLowercase($input);
    $input = ucwords($input);
    return $input;
}

function encryptPassword($input) {
    return sha1( $input );
}