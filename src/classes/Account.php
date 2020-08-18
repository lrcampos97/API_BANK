<?php

class Account {

    public $id;
    public $balance;
    
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

    public function setbalance($value, $type){
        if ($value > 0 ){
            switch ($type) {
                case 'deposit':

                    $this->balance += $value;
                    break;   

                case 'withdraw': 
                    
                    if (($this->balance - $value) >= 0 ){
                        $this->balance -= $value;
                    } else {
                        $this->balance = 0;
                    }                     

                    break;
            }
            
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