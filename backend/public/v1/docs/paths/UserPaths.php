<?php
use OpenApi\Attributes as OA;
#[OA\Schema(schema:'User',type:'object',properties:[new OA\Property(property:'id',type:'integer'),new OA\Property(property:'full_name',type:'string'),new OA\Property(property:'email',type:'string'),new OA\Property(property:'password',type:'string'),new OA\Property(property:'role',type:'string')])]
class UserSchema {}
#[OA\Schema(schema:'UserInput',type:'object',properties:[new OA\Property(property:'full_name',type:'string'),new OA\Property(property:'email',type:'string'),new OA\Property(property:'password',type:'string'),new OA\Property(property:'role',type:'string')])]
class UserInputSchema {}
#[OA\Get(path:'/api/users',tags:['User'],summary:'List users',parameters:[new OA\Parameter(name:'search',in:'query',required:false,schema:new OA\Schema(type:'string'))],responses:[new OA\Response(response:200,description:'List',content:new OA\JsonContent(type:'array',items:new OA\Items(ref:'#/components/schemas/User'))),new OA\Response(response:500,description:'Server error')])]
class UserOperation1 {}
#[OA\Get(path:'/api/users/{id}',tags:['User'],summary:'Get user by ID',parameters:[new OA\Parameter(name:'id',in:'path',required:true,schema:new OA\Schema(type:'integer'))],responses:[new OA\Response(response:200,description:'Found',content:new OA\JsonContent(ref:'#/components/schemas/User')),new OA\Response(response:404,description:'Not found'),new OA\Response(response:500,description:'Internal server error')])]
class UserOperation2 {}
#[OA\Post(path:'/api/users',tags:['User'],summary:'Create user',requestBody:new OA\RequestBody(required:true,content:new OA\JsonContent(ref:'#/components/schemas/UserInput')),responses:[new OA\Response(response:201,description:'Created'),new OA\Response(response:400,description:'Invalid request'),new OA\Response(response:409,description:'Duplicate record'),new OA\Response(response:500,description:'Internal server error')])]
class UserOperation3 {}
#[OA\Put(path:'/api/users/{id}',tags:['User'],summary:'Update user',parameters:[new OA\Parameter(name:'id',in:'path',required:true,schema:new OA\Schema(type:'integer'))],requestBody:new OA\RequestBody(required:true,content:new OA\JsonContent(ref:'#/components/schemas/UserInput')),responses:[new OA\Response(response:200,description:'Updated'),new OA\Response(response:400,description:'Invalid request'),new OA\Response(response:404,description:'Not found'),new OA\Response(response:409,description:'Conflict'),new OA\Response(response:500,description:'Internal server error')])]
class UserOperation4 {}
#[OA\Delete(path:'/api/users/{id}',tags:['User'],summary:'Delete user',parameters:[new OA\Parameter(name:'id',in:'path',required:true,schema:new OA\Schema(type:'integer'))],responses:[new OA\Response(response:200,description:'Deleted'),new OA\Response(response:404,description:'Not found'),new OA\Response(response:409,description:'Record is referenced'),new OA\Response(response:500,description:'Internal server error')])]
class UserOperation5 {}
