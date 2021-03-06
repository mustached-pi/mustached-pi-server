<?php

/*
 * (c)2013 The Mustached Pi Project
 */

namespace MPi\Entity;
class Port extends \PBase\Entity\General
{
    
    protected static
        $_t     = 'ports',
        $_dt    = null;
    
    public function timestamp() {
        return \DateTime::createFromFormat('U', $this->timestamp);
    }
    
    public function valid() {
        $q = "SELECT timestamp FROM ports
              WHERE timestamp > :timestamp
              AND house = :house AND num = :num
              ORDER BY timestamp DESC
              LIMIT 0, 1";
        $q = $this->db->prepare($q);
        $q->bindValue(':timestamp', $this->timestamp);
        $q->bindValue(':house',     $this->house,   \PDO::PARAM_INT);
        $q->bindValue(':num',       $this->num,     \PDO::PARAM_INT);
        $q->execute();
        $r = $q->fetch(\PDO::FETCH_NUM);
        if ( $r ) {
            return \DateTime::createFromFormat('U', $r[0]);
        } else {
            return new \DateTime();
        }
    }
    
    public function values(\DateTime $min = null, \DateTime $max = null) {
        $q = "SELECT id FROM input
              WHERE house = :house
              AND   num   = :num
              AND   timestamp >= :min
              AND   timestamp <= :max
              ORDER BY
                timestamp ASC";
        $q = $this->db->prepare($q);
        $q->bindValue(':house', $this->house,   \PDO::PARAM_INT);
        $q->bindValue(':num',   $this->num,     \PDO::PARAM_INT);
        if ( $min ) {
            $q->bindValue(':min', $min->format('U'),  \PDO::PARAM_INT);
        } else {
            $q->bindValue(':min', $this->timestamp()->format('U'),  \PDO::PARAM_INT);
        }
        if ( $max ) {
            $q->bindValue(':max', $max->format('U'),      \PDO::PARAM_INT);
        } else {
            $q->bindValue(':max', $this->valid()->format('U'),      \PDO::PARAM_INT);
        }
        $q->bindValue(':max', $this->valid()->format('U'),      \PDO::PARAM_INT);
        $q->execute();
        $r = [];
        while ( $k = $q->fetch(\PDO::FETCH_NUM) ) {
            $r[] = new Input($k[0]);
        }
        return $r;        
    }
    
    public function onPostCreate() {
        $this->timestamp = time();
    }
    
    public static function last(House $h, $num) {
        global $db;
        $q = "SELECT id FROM ports
              WHERE house = :house
              AND   num   = :num
              ORDER BY timestamp DESC
              LIMIT 0, 1";
        $q = $db->prepare($db);
        $q->bindValue(':house', $h->id);
        $q->bindValue(':num',   $num,   \PDO::PARAM_INT);
        $q->execute();
        $r = $q->fetch(\PDO::FETCH_NUM);
        if (!$r) { return false; }
        return new static($r[0]);
    }
    
    public function users() {
        $r = [];
        foreach ( Property::filter([['house' => $this->id]]) as $property ) {
            $r[] = $property->user();
        }
        return $r;
    }
    
    public function instance() {
        if ( $this->type == INPUT ) {
            return Input::lastValue($this->house, $this->num);
        } else {
            return Output::lastValue($this->house, $this->num);
        }
    }
    
}