<?php

require_once('../src/classes/Account.php');


const SESSION_ACCOUNT = "ACCOUNT";
const RETURN_RESET = "200 OK";
const RETURN_NOT_FOUND = "404 0";
const RETURN_EXISTING_ACCOUNT = "200 ";
const RETURN_DEPOSIT = "201 ";


    function resetSession(){
        session_regenerate_id(true);

        $_SESSION[SESSION_ACCOUNT] = [];

        return RETURN_RESET;
    }

    function addAccount($id, $balance){            
        $account = new Account($id, $balance);

        array_push($_SESSION[SESSION_ACCOUNT], $account->getObjectJSON());
       
        return prepareReturn($account->getObjectJSON());
    }

    function prepareReturn($object){        

        $arr = array('destination'=>$object);

        return RETURN_DEPOSIT . json_encode($arr);
    }

    function existingDestination($id){        
        $recordKey = -1;

        foreach ($_SESSION[SESSION_ACCOUNT] as $key=>$value) {

            $account = json_decode($value);
                        
            if($account->id === $id){
                $recordKey = $key;                   
                break;
            }

        }

        return $recordKey;
    }

    function updateAccount($id, $amount, $arrayKey){             

        $account = json_decode($_SESSION[SESSION_ACCOUNT][$arrayKey]);

        $newAccount = new Account($id, $account->balance);
        $newAccount->setbalance($amount);
        
        unset($_SESSION[SESSION_ACCOUNT][$arrayKey]);

        return addAccount($newAccount->getid(),$newAccount->getbalance());         
    }
    
    function getBalanceFromAccount($id){
        
        $arrayKey = existingDestination($id);

        if ($arrayKey !== -1){

            $account = json_decode($_SESSION[SESSION_ACCOUNT][$arrayKey]);
                          
            return RETURN_EXISTING_ACCOUNT . $account->balance;

        } else {
            return RETURN_NOT_FOUND;
        }
    }


    function verifySession(){
        if(!isset($_SESSION[SESSION_ACCOUNT])){
            resetSession();
        }
    }


?>