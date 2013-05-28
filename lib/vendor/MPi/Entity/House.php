<?php

/*
 * (c)2013 The Mustached Pi Project
 */

namespace MPi\Entity;
class House extends \PBase\Entity\General
{
    
    protected static
        $_t     = 'houses',
        $_dt    = 'houses_data';
    
    public function users() {
        $r = [];
        foreach ( Property::filter(['house' => $this->id]) as $property ) {
            $r[] = $property->user();
        }
        return $r;
    }
    
    public function ports() {
        global $db;
        $q = "  SELECT t.id FROM 
                    ( SELECT * FROM ports
                      WHERE house = :house
                      ORDER BY timestamp DESC
                    ) t
                GROUP BY (t.num)
                ORDER BY num ASC
        ";
        $q = $db->prepare($q);
        $q->bindValue(':house', $this->id);
        $q->execute();
        $r = [];
        while ( $k = $q->fetch(\PDO::FETCH_NUM) ) {
            $t      = new Port($k[0]);
            $r[$t->num] = $t; 
        }
        return $r;
    }
    
    public function instances() {
        $k = $this->ports();
        $r = [];
        foreach ( $k as $a => $b ) {
            $r[$a] = $b->instance();
        }
        return $r;
    }
    
    public function instancesToJSON() {
        $k = $this->instances();
        $r = [];
        foreach ( $k as $n => $y ) {
            $r[$n] = $y->toJSON();
        }
        return $r;
    }
    
    public function toJSON() {
        return [
            'name'      =>  $this->name,
            'address'   =>  $this->address
        ];
    }
    
    public function events() {
        return Event::filter([
            'house' =>  $this->id
        ], 'start DESC');
    }
    
    public function lastEvents( $num = 10 ) {
        $num = (int) $num;
        return Event::filter([
            'house' =>  $this->id
        ], "start DESC LIMIT 0, {$num}");
    }
    
    public function lastEvent() {
        $k = $this->lastEvents(1);
        if ( !$k ) {
            return false;
        } else {
            return $k[0];
        }
    }
    
}