
<?php

require_once __DIR__ . '/../utils/exceptions.php';

foreach (['User', 'Category', 'Book', 'Loan', 'Reservation', 'Fine'] as $resource) {
    require_once __DIR__ . "/../services/{$resource}Service.php";
}

require_once __DIR__ . '/../services/OpenLibraryService.php';

function request_body(): array
{
    $raw = file_get_contents('php://input');

    if ($raw === '' || $raw === false) {
        throw new ValidationException('JSON request body is required.');
    }

    $decoded = json_decode($raw, true);

    if (
        json_last_error() !== JSON_ERROR_NONE ||
        !is_array($decoded) ||
        array_is_list($decoded)
    ) {
        throw new ValidationException('JSON body must be an object.');
    }

    return $decoded;
}

function send_json(mixed $value, int $status = 200): void
{
    Flight::json($value, $status);
}

function register_api_routes(): void
{
    $resources = [
        'users' => new UserService(),
        'categories' => new CategoryService(),
        'books' => new BookService(),
        'loans' => new LoanService(),
        'reservations' => new ReservationService(),
        'fines' => new FineService()
    ];

    foreach ($resources as $url => $service) {
        Flight::route("GET /api/{$url}", function () use ($service) {
            $list = $service->get_all();

            $search = trim(
                (string) (Flight::request()->query['search'] ?? '')
            );

            if ($search !== '') {
                $list = array_values(array_filter(
                    $list,
                    fn($row) => stripos(json_encode($row), $search) !== false
                ));
            }

            send_json($list);
        });

        Flight::route("GET /api/{$url}/@id:[0-9]+", function ($id) use ($service) {
            send_json($service->get_by_id((int) $id));
        });

        Flight::route("POST /api/{$url}", function () use ($service) {
            send_json($service->add(request_body()), 201);
        });

        Flight::route("PUT /api/{$url}/@id:[0-9]+", function ($id) use ($service) {
            send_json($service->update(request_body(), (int) $id));
        });

        Flight::route("DELETE /api/{$url}/@id:[0-9]+", function ($id) use ($service) {
            send_json($service->delete((int) $id));
        });
    }

    Flight::route('GET /api/openlibrary/@isbn:[0-9Xx-]+', function ($isbn) {
        try {
            send_json((new OpenLibraryService())->lookup($isbn));
        } catch (ValidationException | NotFoundException | ExternalApiException $exception) {
            send_json([
                'status' => $exception->getCode(),
                'error' => $exception->getMessage()
            ], (int) $exception->getCode());
        } catch (Throwable $exception) {
            error_log('[ShelfWise] Open Library: ' . $exception->getMessage());

            send_json([
                'status' => 500,
                'error' => 'Internal server error.'
            ], 500);
        }
    });

    Flight::route('GET /api/health', function () {
        send_json([
            'status' => 'ok',
            'project' => 'ShelfWise',
            'milestone' => 3
        ]);
    });
}
