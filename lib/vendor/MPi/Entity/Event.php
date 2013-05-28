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
    
    public function stop() {
        if ( $this->stop ) {
            return \DateTime::createFromFormat('U', $this->stop);
        } else {
            return false;
        }
    }
    
    public function alive() {
        return !((bool) $this->stop);
    }
    
    public function upload($movie) {
        @mkdir('upload');
        $name = sha1 ( rand(1000, 9999) . microtime() );
        $move = move_uploaded_file($movie['tmp_name'], "upload/{$name}");
        $this->movie = $name;
        return $move;
    }
    
}