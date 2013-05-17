<?php

/*
 * (c)2013 The Mustached Pi Project
 */

namespace MPi\Entity;
class Output extends \PBase\Entity\General
{
    
    public function onPostCreate() {
        $this->timestamp = time();
    }
    
    protected static
        $_t     = 'output',
        $_dt    = null;
    
    public function house() {
        return new House($this->house);
    }
    
    public function timestamp() {
        return \DateTime::createFromFormat('U', $this->timestamp);
    }

    public function value() {
        return (bool) $this->value;
    }
    
    public static function lastValue( $house, $num ) {
        global $db;
        $q = "
            SELECT  id
            FROM    output
            WHERE   house   = :house
            AND     num     = :num
            ORDER BY
                timestamp DESC
            LIMIT 0, 1";
        $q = $db->prepare($q);
        $q->bindValue(':house', $house);
        $q->bindValue(':num',   $num,  \PDO::PARAM_INT);
        $q->execute();
        $r = $q->fetch( \PDO::FETCH_NUM );
        if (!$r) { return false; }
        return new static($r[0]);
    }
    
    public function toJSON() {
        return [
            'type'  =>  'output',
            'value' =>  (int) $this->value
        ];
    }
            
}