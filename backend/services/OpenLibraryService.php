
<?php

require_once __DIR__ . '/../utils/exceptions.php';

class OpenLibraryService
{
    public function lookup(string $isbn): array
    {
        $isbn = str_replace('-', '', trim($isbn));

        if (!preg_match('/^(?:[0-9]{13}|[0-9]{9}[0-9Xx])$/', $isbn)) {
            throw new ValidationException(
                'ISBN must have 10 or 13 characters.'
            );
        }

        $url = 'https://openlibrary.org/isbn/'
            . rawurlencode($isbn)
            . '.json';

        $response = @file_get_contents($url);

        if ($response === false) {
            $status = 0;

            if (isset($http_response_header) && is_array($http_response_header)) {
                foreach ($http_response_header as $header) {
                    if (preg_match('~^HTTP/\S+\s+(\d{3})~i', $header, $match)) {
                        $status = (int) $match[1];
                    }
                }
            }

            if ($status === 404) {
                throw new NotFoundException(
                    'ISBN was not found in Open Library.'
                );
            }

            throw new ExternalApiException(
                'Open Library is currently unavailable.'
            );
        }

        $book = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($book)) {
            throw new ExternalApiException(
                'Invalid response from Open Library.'
            );
        }

        return [
            'isbn' => $isbn,
            'title' => $book['title'] ?? null,
            'publish_date' => $book['publish_date'] ?? null,
            'publishers' => $book['publishers'] ?? [],
            'cover_url' => 'https://covers.openlibrary.org/b/isbn/'
                . rawurlencode($isbn)
                . '-M.jpg',
            'source' => 'Open Library'
        ];
    }
}
