<?php

/*
 * (c)2013 The Mustached Pi Project
 */

namespace MPi\Entity;
class Property extends \PBase\Entity\General
{
    
    protected static
        $_t     = 'properties',
        $_dt    = 'properties_data';
    
    public function user() {
        return new User($this->user);
    }
    
    public function house() {
        return new House($this->house);
    }
    
    public function onPostCreate() {
        $this->timestamp = time();
    }
    
}