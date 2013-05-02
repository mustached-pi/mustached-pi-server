<?php

/*
 * ©2013 Alfio Emanuele Fresta
 */

namespace \PBase\Entity;
class General
{
    
    protected
            $db     = null,
            $cache  = null,
            $_v     = [],
            $_cacheable = true;
    
    private static
            $_t     = 'entityName',
            $_dt    = null;
    
    public
            $id;
    
    public function __construct ( $id = null )
    {
        global $db, $cache;
        $this->db = $db;
        if ( $this->_cacheable ) {
            $this->cache = $cache;
        }
        /* Check existance */
        if ( self::__exists($id) ) {
            /* Download database record */
            $this->onBeforeLoad();
            $this->id = $id;
            if ( $this->cache ) {
                if ( $this->_v = unserialize( $this->cache->get( $conf['db_hash'] . static::$_t . ':' . $id . ':___fields') ) ) {
                    return;
                }
            }
            $q = $this->db->prepare("
                SELECT * FROM ". static::$_t ." WHERE id = :id");
            $q->bindParam(':id', $this->id);
            $q->execute();
            $this->_v = $q->fetch(PDO::FETCH_ASSOC);
            if ( $this->cache ) {
                $this->cache->set($conf['db_hash'] . static::$_t . ':' . $id . ':___fields', serialize($this->_v));
            }
            $this->onPostLoad();
        } elseif ( $id === null ) {
            /* Create a new one */
            $this->__create();
            $this->__construct($this->id);
        } else {
            /* Doesnt exist ! */
            $e = new \PBase\Exception\General(404);
            $e->extra = static::$_t. ':' . $id;
            throw $e;
        }
    }
    
    public static function by($_name, $_value)
    {
        global $db;
        $entita = get_called_class();
        $q = $db->prepare("
            SELECT id FROM ". static::$_t . " WHERE $_name = :value");
        $q->bindParam(':value', $_value);
        $q->execute();
        $r = $q->fetch(PDO::FETCH_NUM);
        if (!$r) { return false; }
        return new $entita($r[0]);
    }

    public static function filter($_array, $_order = null)
    {
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
        if ( $_order ) {
            $_order = 'ORDER BY ' . $_order;
        }
        $q = $db->prepare("
            SELECT id FROM ". static::$_t . " WHERE $string $_order");
        $q->execute();
        $t = [];
        while ( $r = $q->fetch(PDO::FETCH_NUM) ) {
            $t[] = new $entity($r[0]);
        }
        return $t;
    }
    
    public static function all($order = '')
    {
        global $db;
        $entity = get_called_class();
        if ( $order ) { 
            $order = 'ORDER BY ' . $ordine;
        }
        $q = $db->prepare("
            SELECT id FROM ". static::$_t . " ". $ordine);
        $q->execute();
        $t = [];
        while ( $r = $q->fetch(PDO::FETCH_NUM) ) {
            $t[] = new $entity($r[0]);
        }
        return $t;
    }
    
    /* Not yet working.
    public static function cercaFulltext($query, $campi, $limit = 20, $altroWhere = '') {
        global $db;
        $entita = get_called_class();
        //var_dump(count($campi), str_word_count($campi[0]));
        if (count($campi) == 1 AND str_word_count($query) == 1) {
            $stringa = " WHERE {$campi[0]} LIKE :stringa";
            $query = '%' . $query . '%';
        } else {
            $stringa = " WHERE MATCH (" . implode(', ', $campi) .") AGAINST (:stringa)";
        }
        $q = $db->prepare("
            SELECT id FROM ". static::$_t . " ". $stringa . " " . $altroWhere . " LIMIT 0,$limit");
        $q->bindParam(':stringa', $query);
        $q->execute();
        $t = [];
        while ( $r = $q->fetch(PDO::FETCH_NUM) ) {
            $t[] = new $entita($r[0]);
        }
        return $t;
    } */
    
    public function __toString()
    {
        return $this->id;
    }
    
    public static function __exists ( $id = null )
    {
        if (!$id) { return false; }
        global $db, $cache, $conf;
        if ($cache) {
            if ( $cache->get($conf['db_hash'] . static::$_t . ':' . $id) ) {
                return true;
            }
        }
        $q = $db->prepare("
            SELECT id FROM ". static::$_t ." WHERE id = :id");
        $q->bindParam(':id', $id);
        $q->execute();
        $y = (bool) $q->fetch(PDO::FETCH_NUM);
        if ($cache && $y) {
            $cache->set($conf['db_hash'] . static::$_t . ':' . $id, 'true');
        }
        return $y;
    }
    
    /*
     * Overrideable ID generator function
     */
    protected function generateID()
    {
        $q = $this->db->prepare("
            SELECT MAX(id) FROM ". static::$_t );
        $q->execute();
        $r = $q->fetch(PDO::FETCH_NUM);
        if (!$r) { $r[0] = 0; }
        return (int) $r[0] + 1;
    }
    
    protected function __create ()
    { 
        $this->onBeforeCreate();
        do {
            $this->id = $this->generateID();
        } while ( $this->__exists($this->id) );
        $q = $this->db->prepare("
            INSERT INTO ". static::$_t ."
            (id) VALUES (:id)");
        $q->bindParam(':id', $this->id);
        $r = $q->execute();
        $this->onPostCreate();
        return $r;
    }
    
    public function __get ( $_name )
    {
        global $conf;
        $this->onBeforeGet($_name);
        if ( $this->cache ) {
            $r = $this->cache->get($conf['db_hash'] . static::$_t . ':' . $this->id . ':' . $_name);
            if ( $r !== false ) {
                return $r;
            }
        }
        if (array_key_exists($_name, $this->_v) ) {
            /* Internal property */
            $q = $this->db->prepare("
                SELECT $_name FROM ". static::$_t ." WHERE id = :id");
            $q->bindParam(':id', $this->id);
            $q->execute();
            $r = $q->fetch(PDO::FETCH_NUM);
            $r = $r[0];

        } else {
            /* Linked property */
            $q = $this->db->prepare("
                SELECT value FROM ". static::$_dt ." WHERE id = :id AND name = :name");
            $q->bindParam(':id', $this->id);
            $q->bindParam(':name', $_name);
            $q->execute();
            $r = $q->fetch(PDO::FETCH_NUM);
            if ($r) {
                $r = $r[0];
            } else {
                $r = null;
            }
        }
        if ( $this->cache ) {
            $this->cache->set($conf['db_hash'] . static::$_t . ':' . $this->id . ':' . $_name, $r);
        }
        $this->onPostGet($_name);
        return $r;
    }
    

    public function __set ( $_name, $_value )
    {
        global $conf;
        $this->onBeforeSet( $_name, $_value );
        if ( array_key_exists($_name, $this->_v) ) {
            /* Internal property */
            $q = $this->db->prepare("
                UPDATE ". static::$_t ." SET $_name = :valore WHERE id = :id");
            $q->bindParam(':valore', $_value);
            $q->bindParam(':id', $this->id);
            $q->execute();
            $this->_v[$_name] = $_value;
        } else {
            /* External property */
            if ( $_value === null ) {
                /* Delete */
                $q = $this->db->prepare("
                    DELETE FROM ". static::$_dt ." WHERE id = :id AND name = :name");
                $q->bindParam(':id', $this->id);
                $q->bindParam(':name', $_name);
                $q->execute();
            } else {
                $prec = $this->$_name;
                if ( $prec === null ) {
                    /* Insert! */
                    $q = $this->db->prepare("
                        INSERT INTO ". static::$_dt ."
                        (id, name, value)
                        VALUES
                        (:id, :name, :value)");
                } else {
                    /* Update */
                    $q = $this->db->prepare("
                        UPDATE ". static::$_dt ." SET
                        value = :value
                        WHERE id = :id
                        AND   name = :name");
                }
                $q->bindParam(':id', $this->id);
                $q->bindParam(':name', $_name);
                $q->bindParam(':value', $_value);
                $q->execute();                
            }

        }
        if ( $this->cache ) {
            $this->cache->set($conf['db_hash'] . static::$_t . ':' . $this->id . ':' . $_name, $_value);
        }
        $this->onPostSet($_name, $_value);
    }
    
    public function delete()
    {
        global $conf;
        $this->onBeforeDelete();
        $this->deleteDetails();
        $q = $this->db->prepare("
            DELETE FROM ". static::$_t ." WHERE id = :id");
        $q->bindParam(':id', $this->id);
        $q->execute();
        if ( $this->cache ) {
            $this->cache->delete($conf['db_hash'] . static::$_t . ':' . $this->id);
        }
        $this->onPostDelete();
    }
    
    private function deleteDetails()
    {
        if ( !static::$_dt ) { return true; }
        $q = $this->db->prepare("
            DELETE FROM ". static::$_dt ." WHERE id = :id");
        $q->bindParam(':id', $this->id);
        return $q->execute();
    }
    
    /* Event ovveridable functions */
    
    public function onBeforeCreate  () {}
    public function onPostCreate    () {}
    
    public function onBeforeLoad    () {}
    public function onPostLoad      () {}
    
    public function onBeforeSet     ( $name = null, $value = null ) {}
    public function onPostSet       ( $name = null, $value = null ) {}
    
    public function onBeforeGet     ( $name = null ) {}
    public function onPostGet       ( $name = null ) {}
    
    public function onBeforeDelete  () {}
    public function onPostDelete    () {}

}