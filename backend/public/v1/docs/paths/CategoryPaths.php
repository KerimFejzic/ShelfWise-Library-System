<?php
use OpenApi\Attributes as OA;
#[OA\Schema(schema:'Category',type:'object',properties:[new OA\Property(property:'id',type:'integer'),new OA\Property(property:'name',type:'string'),new OA\Property(property:'description',type:'string')])]
class CategorySchema {}
#[OA\Schema(schema:'CategoryInput',type:'object',properties:[new OA\Property(property:'name',type:'string'),new OA\Property(property:'description',type:'string')])]
class CategoryInputSchema {}
#[OA\Get(path:'/api/categories',tags:['Category'],summary:'List categories',parameters:[new OA\Parameter(name:'search',in:'query',required:false,schema:new OA\Schema(type:'string'))],responses:[new OA\Response(response:200,description:'List',content:new OA\JsonContent(type:'array',items:new OA\Items(ref:'#/components/schemas/Category'))),new OA\Response(response:500,description:'Server error')])]
class CategoryOperation1 {}
#[OA\Get(path:'/api/categories/{id}',tags:['Category'],summary:'Get category by ID',parameters:[new OA\Parameter(name:'id',in:'path',required:true,schema:new OA\Schema(type:'integer'))],responses:[new OA\Response(response:200,description:'Found',content:new OA\JsonContent(ref:'#/components/schemas/Category')),new OA\Response(response:404,description:'Not found'),new OA\Response(response:500,description:'Internal server error')])]
class CategoryOperation2 {}
#[OA\Post(path:'/api/categories',tags:['Category'],summary:'Create category',requestBody:new OA\RequestBody(required:true,content:new OA\JsonContent(ref:'#/components/schemas/CategoryInput')),responses:[new OA\Response(response:201,description:'Created'),new OA\Response(response:400,description:'Invalid request'),new OA\Response(response:409,description:'Duplicate record'),new OA\Response(response:500,description:'Internal server error')])]
class CategoryOperation3 {}
#[OA\Put(path:'/api/categories/{id}',tags:['Category'],summary:'Update category',parameters:[new OA\Parameter(name:'id',in:'path',required:true,schema:new OA\Schema(type:'integer'))],requestBody:new OA\RequestBody(required:true,content:new OA\JsonContent(ref:'#/components/schemas/CategoryInput')),responses:[new OA\Response(response:200,description:'Updated'),new OA\Response(response:400,description:'Invalid request'),new OA\Response(response:404,description:'Not found'),new OA\Response(response:409,description:'Conflict'),new OA\Response(response:500,description:'Internal server error')])]
class CategoryOperation4 {}
#[OA\Delete(path:'/api/categories/{id}',tags:['Category'],summary:'Delete category',parameters:[new OA\Parameter(name:'id',in:'path',required:true,schema:new OA\Schema(type:'integer'))],responses:[new OA\Response(response:200,description:'Deleted'),new OA\Response(response:404,description:'Not found'),new OA\Response(response:409,description:'Record is referenced'),new OA\Response(response:500,description:'Internal server error')])]
class CategoryOperation5 {}
