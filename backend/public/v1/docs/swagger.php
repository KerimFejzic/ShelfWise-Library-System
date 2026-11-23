
<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

// Load OpenAPI documentation
require_once __DIR__ . '/doc_setup.php';

foreach (glob(__DIR__ . '/paths/*.php') as $file) {
    require_once $file;
}

header('Content-Type: application/json; charset=utf-8');

try {
    // Generate OpenAPI specification
    $generator = new \OpenApi\Generator();

    $openapi = $generator->generate([
        __DIR__ . '/doc_setup.php',
        __DIR__ . '/paths'
    ]);

    // Convert the specification to a PHP array
    $data = json_decode(
        $openapi->toJson(),
        true,
        512,
        JSON_THROW_ON_ERROR
    );

    // Configure the API server URL
    $uri = $_SERVER['SCRIPT_NAME']
        ?? '/public/v1/docs/swagger.php';

    $base = preg_replace(
        '~/public/v1/docs/swagger\.php$~',
        '',
        $uri
    );

    $data['servers'] = [
        [
            'url' => $base ?: '/',
            'description' => 'ShelfWise API'
        ]
    ];

    echo json_encode(
        $data,
        JSON_PRETTY_PRINT |
        JSON_UNESCAPED_SLASHES |
        JSON_THROW_ON_ERROR
    );

} catch (\Throwable $e) {
    http_response_code(500);

    error_log(
        'Swagger generation failed: ' . $e->getMessage()
    );

    echo json_encode([
        'status' => 500,
        'error' => 'Failed to generate OpenAPI documentation'
    ]);
}
