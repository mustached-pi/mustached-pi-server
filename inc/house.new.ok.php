<?php

/*
 * ©2013 The Mustached Pi Project
 */

$name       = toNamestring($_POST['inputName']);
$address    = toLowercase($_POST['inputAddress']);
$code       = toLowercase($_POST['inputCode']);

$address    = new \PBase\Tool\Geocoder($address);
$address    = $address->results[0];
$address    = $address->formattato;

$h = new \MPi\Entity\House();
$h->name    = $name;
$h->address = $address;
$h->code    = $code;

$p = new \MPi\Entity\Property();
$p->user        = $me;
$p->house       = $h;
$p->timestamp   = time();

redirect('home');