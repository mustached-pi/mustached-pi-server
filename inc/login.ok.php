<?php

/*
 * ©2013 The Mustached Pi Project
 */

$email  = toLowercase($_POST['inputEmail']);
$password   = $_POST['inputPassword'];

if ( $u = MPi\Entity\User::by('email', $email) ) {
    
    if ( $u->password == encryptPassword($password) ) {
        $session->user = $u;
        $session->touch();
        redirect('home');
    } else {
        redirect('login&e2');
    }
    
} else {
    redirect('login&e1');
}
