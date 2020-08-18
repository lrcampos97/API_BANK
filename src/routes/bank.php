<?php
header('Access-Control-Allow-Headers: "Origin, X-Requested-With, Content-Type, Accept"');
header("Access-Control-Allow-Origin: *", false);
header("Access-Control-Allow-Methods: POST, GET");

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\StreamInterface;


require_once('../src/data/data.php');

// RESET SESSION AND DATA
$app->post('/reset', function(Request $request, Response $response){       
         
    return $response->write(resetSession());      
 
});


$app->get('/balance', function(Request $request, Response $response){        

    
    if(isset($_GET['account_id'])){

        
        $result = getBalanceFromAccount($_GET['account_id']);

        if ($result !== -1){
            $response->write($result);

            return $response->withStatus(RETURN_EXISTING_ACCOUNT);           
        }  else {
            $response->write(0);

            return $response->withStatus(RETURN_NOT_FOUND);                
        }     
    }
        
});


$app->post('/event', function(Request $request, Response $response, $args){
    $body = $request->getBody();

    $values = json_decode($body, true);


    if (isset($values["type"]) && $values["type"] !== ""){
        
        verifySession();

        switch ($values["type"]) {

            case "deposit":

                 $arrayKey = existingDestination($values["destination"]);
                 
                 if ($arrayKey !== -1){        
                                         
                    $arrReturn = array("destination"=>updateAccount($values["destination"], $values["amount"], $arrayKey, $values["type"]));
                                                        
                    $response->write(json_encode($arrReturn));

                    return $response->withStatus(RETURN_DEPOSIT);                                      
                 } else {

                    $arrReturn = array("destination"=>addAccount($values["destination"], $values["amount"], $values["type"]));
                    
                    $response->write(json_encode($arrReturn));

                    return $response->withStatus(RETURN_DEPOSIT);  
                 }               

                break;

            case "withdraw":

                $arrayKey = existingDestination($values["origin"]);

                if ($arrayKey !== -1){

                    $arrReturn = array("origin"=> withdrawAccount($values["origin"], $values["amount"], $arrayKey));
           
                    $response->write(json_encode($arrReturn));

                    return $response->withStatus(RETURN_DEPOSIT);  
                                                            
                  } else {                     
                    $response->write(0);

                    return $response->withStatus(RETURN_NOT_FOUND);         
                  }  
                
                break;  

            case "transfer":
                $result = transferAmount($values["origin"], $values["amount"], $values["destination"]);

                if ($result !== RETURN_NOT_FOUND){

                    $response->write($result);

                    return $response->withStatus(RETURN_DEPOSIT);                      
                } else {

                    $response->write(0);

                    return $response->withStatus(RETURN_NOT_FOUND);  
                }

                break;

            default:
                $response->write(0);

                return $response->withStatus(RETURN_NOT_FOUND);  
                break;
        };

    } else {
        $response->write(0);

        return $response->withStatus(RETURN_NOT_FOUND);  
    }
});



