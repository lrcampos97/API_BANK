<?php

class Account {

    private $id;
    private $balance;
    
    public  function __construct($id, $amount){
        
        if ($id !== 0){
            $this->id = $id;
        }

        if($amount >= 0){
            $this->balance = $amount;
        }

    }

    // GETTERS and SETTERS
    public function setid($value){
        $this->id = $value;
    }

    public function setbalance($value){
        if ($value > 0 ){
            $this->balance += $value;
        }    
    }

    public function getid(){
        return $this->id;
    }

    public function getbalance(){
        return $this->balance;
    }    

    public function getObjectJSON() {        
        return json_encode(get_object_vars($this),JSON_FORCE_OBJECT);
    }

}

?>