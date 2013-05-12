<?php

/*
 * (c)2013 The Mustached Pi Project
 */

namespace MPi\Entity;
class Session extends \PBase\Entity\Session
{
        
    public function user() {
        if  ( $this->user ) {
            return new User($this->user);
        } else {
            return false;
        }
    }
    
    public function unregisterSession() {
        $this->user = null;
    }
    
}