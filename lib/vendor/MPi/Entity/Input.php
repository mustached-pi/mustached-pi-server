<?php

/*
 * (c)2013 The Mustached Pi Project
 */

namespace MPi\Entity;
class Input extends \PBase\Entity\General
{
    
    public function onPostCreate() {
        $this->timestamp = time();
    }
    
    protected static
        $_t     = 'input',
        $_dt    = null;
    
    public function house() {
        return new House($this->house);
    }
    
    public function timestamp() {
        return \DateTime::createFromFormat('U', $this->timestamp);
    }

    public function percent() {
        return (int) ( $this->value / 255 * 100 );
    }
    
    public static function lastValue( $house, $num ) {
        global $db;
        $q = "
            SELECT  id
            FROM    input
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
            'type'  =>  'input'
        ];
    }
            
}