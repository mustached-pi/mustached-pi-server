<?php

/*
 * (c)2013 The Mustached Pi Project
 */

namespace \MPi\Entity;
class House extends \PBase\Entity\General
{
    
    public function users() {
        $r = [];
        foreach ( Property::filter([['house' => $this->id]]) as $property ) {
            $r[] = $property->user();
        }
        return $r;
    }
            
}