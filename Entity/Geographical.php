<?php

/*
 * ©2013 Alfio Emanuele Fresta
 */

namespace \PBase\Entity;
class Geographical extends General
{
    
    public function coordinates() {
        $q = $this->db->prepare("
            SELECT X(geo), Y(geo) FROM ". static::$_t . " WHERE id = :id");
        $q->bindParam(':id', $this->id);
        $q->execute();
        if ( $r = $q->fetch(PDO::FETCH_NUM) ) {
            return [$r[0], $r[1]];
        } else {
            return [false, false];
        }
    }
    
    public function latlng() {
        return $this->coordinates()[0].', '.$this->coordinates()[1];
    }
    
    public function locateByCoordinates($x, $y) {
        $x = (double) $x;
        $y = (double) $y;
        $q = $this->db->prepare("
            UPDATE ". static::$_t . " SET geo = GeomFromText('POINT({$x} {$y})') WHERE id = :id");
        $q->bindParam(':id', $this->id);
        return $q->execute();
    }
    
    public function locateByString($string) {
        $g = new Geocoder($string);
        if (!$g->results) { return false; }
        return $this->locateByCoordinates($g->results[0]->lat, $g->results[0]->lng);
    }
    
    public static function filterByRadius ( $lat, $lng, $radius, $_array ) {
        $lat = (double) $lat;
        $lng = (double) $lng;
        $radius = (float) $radius;
        global $db;
        $entity = get_called_class();
        $_conditions = [];
        foreach ( $_array as $_elem ) {
            if ( $_elem[1] == null ) {
                $_conditions[] = "{$_elem[0]} IS NULL";
            } else {
                $_conditions[] = "{$_elem[0]} = '{$_elem[1]}'";
            }
        }
        $string = implode(' AND ', $_conditions);
        $centro = "GeomFromText(\"POINT({$lat} {$lng})\")"; 
        $q = $db->prepare("
            SELECT id FROM ". static::$_t . " WHERE 
                SQRT(POW( ABS( X(geo) - X($centro)), 2) + POW( ABS(Y(geo) - Y($centro)), 2 )) < $radius
              AND
                $string");
        $q->execute();
        $t = [];
        while ( $r = $q->fetch(PDO::FETCH_NUM) ) {
            $t[] = new $entity($r[0]);
        }
        return $t;
    }
    
    public function hasLocation() {
        $c = $this->coordinates();
        return (bool) $c[0] && $c[1];
    }
    
    protected function __create () { 
        $this->id = $this->generateID();
        $q = $this->db->prepare("
            INSERT INTO ". static::$_t ."
            (id, geo) VALUES (:id, GeomFromText('POINT (0 0)'))");
        $q->bindParam(':id', $this->id);
        return $q->execute();
    }
    
}