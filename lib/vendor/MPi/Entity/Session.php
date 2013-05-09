<?php

/*
 * (c)2013 The Mustached Pi Project
 */

namespace MPi\Entity;
class Session extends \PBase\Entity\Session
{
    
    protected static
        $_t     = 'sessions',
        $_dt    = 'sessions_data';
    
    public function user() {
        return new User($this->user);
    }
    
}