<?php
declare(strict_types=1);
use OpenApi\Attributes as OA;
#[OA\Info(version:'1.0.0',title:'ShelfWise API',description:'Milestone 3 REST API for library management')]
#[OA\Server(url: '/backend',description: 'Override the base URL in Swagger UI when your project is nested in XAMPP')]
#[OA\Schema(schema:'ApiError',type:'object',required:['status','error'],properties:[new OA\Property(property:'status',type:'integer'),new OA\Property(property:'error',type:'string')])]
class ShelfWiseDocsSetup {}
