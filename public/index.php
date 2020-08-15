<?php

session_start(); // iniciar uma sessão

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$app = new \Slim\App;

require_once('../src/routes/bank.php');

$app->run();
