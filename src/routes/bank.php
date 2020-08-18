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

        return getBalanceFromAccount($_GET['account_id']);
        
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
                    
                   return updateAccount($values["destination"], $values["amount"], $arrayKey);      
                                      
                 } else {
                    
                    return addAccount($values["destination"], $values["amount"]);    

                 }               

                break;
            default:
                return RETURN_NOT_FOUND;;
                break;
        };

    } else {
        return RETURN_NOT_FOUND;
    }

});



