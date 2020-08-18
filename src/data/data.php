<?php
require_once('../src/classes/Account.php');


const SESSION_ACCOUNT = "ACCOUNT";
const RETURN_RESET = "OK";
const RETURN_NOT_FOUND = 404;
const RETURN_EXISTING_ACCOUNT = 200;
const RETURN_DEPOSIT = 201;    


    // RESET A NEW EMPTY SESSION
    function resetSession(){
        session_start(); // iniciar uma sessão

        session_regenerate_id();
    
        $_SESSION[SESSION_ACCOUNT] = [];

        return RETURN_RESET;
    }
    

    // ADD A NEW ACCOUNT IN THE ARRAY
    function addAccount($id, $balance, $type){

        $account = new Account($id, $balance);

        array_push($_SESSION[SESSION_ACCOUNT], $account->getObjectJSON());
        
        return $account;               
    }

    // CHECK IF THE DESTINATION EXISTS BY RETURNING THE REGISTRATION INDEX IN THE ARRAY
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

    // UPDATE ACCOUNT BY REMOVING THE OLD AND ADDING THE NEW.
    function updateAccount($id, $amount, $arrayKey, $type){             

        $account = json_decode($_SESSION[SESSION_ACCOUNT][$arrayKey]);

        $newAccount = new Account($id, $account->balance);
        $newAccount->setbalance($amount, $type);
        
        unset($_SESSION[SESSION_ACCOUNT][$arrayKey]);

        return addAccount($newAccount->getid(), $newAccount->getbalance(), $type);         
    }
    
    // GET BALANCE FROM EXISTING ACCOUNTS
    function getBalanceFromAccount($id){

        resetSession();
        
        $arrayKey = existingDestination($id);

        if ($arrayKey !== -1){

            $account = json_decode($_SESSION[SESSION_ACCOUNT][$arrayKey]);
                          
            return $account->balance;

        } else {
            return -1;
        }
    }

    // CHECK IF SESSION IS STILL SET
    function verifySession(){
        if(!isset($_SESSION[SESSION_ACCOUNT])){
            resetSession();
        }
    }

    //WITHDRAW AMOUNT FROM AN ACCOUNT
    function withdrawAccount($origin, $amount, $arrayKey){
       
        return  updateAccount($origin, $amount, $arrayKey, "withdraw");      
                             
    }

    // TRANSFER AMOUNT FROM AN ACCOUNT TO OTHER
    function transferAmount($origin, $amount, $destination){

        $arrayKeyOrigin = existingDestination($origin);
        $arrayKeydestination = existingDestination($destination);

        if (($arrayKeyOrigin !== -1) && ($arrayKeydestination !== -1)){
                        
            $returnWithdraw = withdrawAccount($origin, $amount, $arrayKeyOrigin);
            $returnDeposit = updateAccount($destination, $amount, $arrayKeydestination, "deposit");

            $arrReturn = array("origin"=>$returnWithdraw,"destination"=>$returnDeposit);
            
            return json_encode($arrReturn);
        } else {
           
           return RETURN_NOT_FOUND;

        }       
    }

?>