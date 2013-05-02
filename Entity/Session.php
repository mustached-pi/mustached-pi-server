<?php

/*
 * ©2013 Alfio Emanuele Fresta
 */

namespace \PBase\Entity;
abstract class Session extends General
{
    public function generateID()
    {
        return sha1(
               microtime() .
               rand(1000, 9999)
        );
    }
    
    
}