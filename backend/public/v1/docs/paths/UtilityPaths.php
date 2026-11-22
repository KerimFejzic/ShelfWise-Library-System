<?php
use OpenApi\Attributes as OA;
#[OA\Get(path:'/api/health',tags:['Utility'],summary:'API health check',responses:[new OA\Response(response:200,description:'API is running'),new OA\Response(response:500,description:'Internal server error')])]
class UtilityOperation1 {}
#[OA\Get(path:'/api/openlibrary/{isbn}',tags:['External API'],summary:'Look up book metadata by ISBN from Open Library',parameters:[new OA\Parameter(name:'isbn',in:'path',required:true,schema:new OA\Schema(type:'string'))],responses:[new OA\Response(response:200,description:'Metadata'),new OA\Response(response:400,description:'Bad ISBN'),new OA\Response(response:404,description:'Not found'),new OA\Response(response:502,description:'Upstream unavailable'),new OA\Response(response:500,description:'Internal server error')])]
class UtilityOperation2 {}
