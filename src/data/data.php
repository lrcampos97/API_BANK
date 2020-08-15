<?php

require_once('../src/classes/Account.php');


const SESSION_ACCOUNT = "ACCOUNT";
const RETURN_RESET = "200 OK";
const RETURN_NOT_FOUND = "404 0";


    function resetSession(){
        session_regenerate_id(true);

        $_SESSION[SESSION_ACCOUNT] = [];

        return RETURN_RESET;
    }

    function addAccount($id, $balance){
        
        verifySession();

        $account = new Account($id, $balance);

        array_push($_SESSION[SESSION_ACCOUNT], $account);
       
        return prepareReturn($account->getObjectJSON());
    }

    function prepareReturn($object){        

        $arr = array('destination'=>$object);

        return "201 " . json_encode($arr);
    }

    function verifySession(){

        if(!isset($_SESSION[SESSION_ACCOUNT])){
            resetSession();
        }
    }


?>