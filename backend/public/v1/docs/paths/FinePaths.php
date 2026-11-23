<?php
use OpenApi\Attributes as OA;
#[OA\Schema(schema:'Fine',type:'object',properties:[new OA\Property(property:'id',type:'integer'),new OA\Property(property:'loan_id',type:'integer'),new OA\Property(property:'amount',type:'number'),new OA\Property(property:'status',type:'string')])]
class FineSchema {}
#[OA\Schema(schema:'FineInput',type:'object',properties:[new OA\Property(property:'loan_id',type:'integer'),new OA\Property(property:'amount',type:'number'),new OA\Property(property:'status',type:'string')])]
class FineInputSchema {}
#[OA\Get(path:'/api/fines',tags:['Fine'],summary:'List fines',parameters:[new OA\Parameter(name:'search',in:'query',required:false,schema:new OA\Schema(type:'string'))],responses:[new OA\Response(response:200,description:'List',content:new OA\JsonContent(type:'array',items:new OA\Items(ref:'#/components/schemas/Fine'))),new OA\Response(response:500,description:'Server error')])]
class FineOperation1 {}
#[OA\Get(path:'/api/fines/{id}',tags:['Fine'],summary:'Get fine by ID',parameters:[new OA\Parameter(name:'id',in:'path',required:true,schema:new OA\Schema(type:'integer'))],responses:[new OA\Response(response:200,description:'Found',content:new OA\JsonContent(ref:'#/components/schemas/Fine')),new OA\Response(response:404,description:'Not found'),new OA\Response(response:500,description:'Internal server error')])]
class FineOperation2 {}
#[OA\Post(path:'/api/fines',tags:['Fine'],summary:'Create fine',requestBody:new OA\RequestBody(required:true,content:new OA\JsonContent(ref:'#/components/schemas/FineInput')),responses:[new OA\Response(response:201,description:'Created'),new OA\Response(response:400,description:'Invalid request'),new OA\Response(response:409,description:'Duplicate record'),new OA\Response(response:500,description:'Internal server error')])]
class FineOperation3 {}
#[OA\Put(path:'/api/fines/{id}',tags:['Fine'],summary:'Update fine',parameters:[new OA\Parameter(name:'id',in:'path',required:true,schema:new OA\Schema(type:'integer'))],requestBody:new OA\RequestBody(required:true,content:new OA\JsonContent(ref:'#/components/schemas/FineInput')),responses:[new OA\Response(response:200,description:'Updated'),new OA\Response(response:400,description:'Invalid request'),new OA\Response(response:404,description:'Not found'),new OA\Response(response:409,description:'Conflict'),new OA\Response(response:500,description:'Internal server error')])]
class FineOperation4 {}
#[OA\Delete(path:'/api/fines/{id}',tags:['Fine'],summary:'Delete fine',parameters:[new OA\Parameter(name:'id',in:'path',required:true,schema:new OA\Schema(type:'integer'))],responses:[new OA\Response(response:200,description:'Deleted'),new OA\Response(response:404,description:'Not found'),new OA\Response(response:409,description:'Record is referenced'),new OA\Response(response:500,description:'Internal server error')])]
class FineOperation5 {}
