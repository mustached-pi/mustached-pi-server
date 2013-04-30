<?php

/*
 * ©2013 Alfio Emanuele Fresta
 */

namespace \PBase\Database;
class ePDO extends PDO
{
    
    public $queryCount = 0;
    
    public function prepare ( $q, $options = [] ) {
        $this->queryCount++;
        return parent::prepare( $q, $options );
    }

    public function __destruct() {
        global $cache, $conf;
        if ( $cache ) {
            $q = (int) $cache->get( $conf['db_hash'] . '__nq' );
            $q += $this->numQuery;
            $cache->set( $conf['db_hash'] . '__nq', $q );
        }
    }
}
