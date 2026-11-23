<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/routes/ApiRoutes.php';
header('Content-Type: application/json; charset=utf-8');
Flight::map('error',function(Throwable $exception){
    $code=(int)$exception->getCode();
    $status=in_array($code,[400,404,409,502],true)?$code:500;
    if ($exception instanceof PDOException && $exception->getCode()==='23000') $status=409;
    error_log('[ShelfWise] '.$exception->getMessage());
    Flight::json(['status'=>$status,'error'=>$status===500?'Internal server error.':($status===409?'Record conflicts with related data.':$exception->getMessage())],$status);
});
Flight::map('notFound',function(){Flight::json(['status'=>404,'error'=>'Endpoint not found.'],404);});
register_api_routes();
Flight::start();
