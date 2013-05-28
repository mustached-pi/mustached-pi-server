<?php

/*
 * (c)2013 The Mustached Pi Project
 */

namespace MPi\Entity;
class Event extends \PBase\Entity\General
{
        
    protected static
        $_t     = 'events',
        $_dt    = null;

    public function house() {
        return new House($this->house);
    }
    
    public function start() {
        return \DateTime::createFromFormat('U', $this->start);
    }
    
    public function end() {
        if ( $this->end ) {
            return \DateTime::createFromFormat('U', $this->end);
        } else {
            return false;
        }
    }
    
    public function alive() {
        return !((bool) $this->end);
    }
    
}