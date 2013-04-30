<?php

/*
 * ©2013 Alfio Emanuele Fresta
 */

namespace \PBase\Exception;
class General extends Exception
{
   
    public
            $code,
            $message,
            $timestamp,
            $extra = null;
    
    public function __construct($code = 1000) {
        global $conf;
        $this->code = $code;
        if (isset($conf['errors'][$code])) {
            $this->message = $conf['errors'][$code];
        } else {
            $this->message = 'General error';
        }
        $this->timestamp = time();
        parent::__construct($this->message, $code, null);
    }
    
    public function __toString() {
        return date('YmdHis', $this->timestamp) . " [ERR #" . $this->code . "]: " . $this->message . "\t{$this->extra}\n";
    }
	
    public function toJSON() {
        return [
            'error' => [
                'code'      =>	$this->code,
                'message'   =>	$this->message,
                'timestamp' =>	$this->timestamp,
                'log'       =>  $this->__toString(),
                'extra'     =>  $this->extra
            ]
        ];
    }
}