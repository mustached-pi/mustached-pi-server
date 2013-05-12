<?php

/*
 * ©2013 The Mustached Pi Project
 */

/*
 * Redirects the user to a different page.
 */
function redirect( $newPage ) {
    header("Location: index.php?p={$newPage}");
    exit(0);
}

/*
 * Requires the user to be logged in
 */
function privatePage() {
    global $session;
    if ( !$session->user ) {
        redirect('login');
    }
}