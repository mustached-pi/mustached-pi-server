<?php

/*
 * (c)2013 The Mustached Pi Project
 */

namespace \MPi\Entity;
class User extends \PBase\Entity\General
{
    
    public function houses() {
        $r = [];
        foreach ( Property::filter([['user' => $this->id]]) as $property ) {
            $r[] = $property->house();
        }
        return $r;
    }
    
    public function onPostDelete() {
        foreach ( Property::filter([['user' => $this->id]]) as $property ) {
            $property->delete();
        }
    }
    
}
