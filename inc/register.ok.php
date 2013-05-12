<?php

/*
 * ©2013 Alfio Emanuele Fresta
 */

$email      =   $_POST['inputEmail'];
$password   =   $_POST['inputPassword'];
$name       =   $_POST['inputName'];

$email      = toLowercase($email);
$name       = toNamestring($name);

/* 
 * Check if the user already exists
 */
if ( MPi\Entity\User::by('email', $email) ) {
    redirect('register.error');
}

$u  = new MPi\Entity\User();
$u->name    = $name;
$u->email   = $email;
$u->timestamp   = time();
$u->changePassword($password);

$session->user  =   $u;
$session->touch();

redirect('home');