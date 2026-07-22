<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/api/categories', function (
    Request $request,
    Response $response
): Response {
    $pdo = getDatabaseConnection();

    $statement = $pdo->query("
        SELECT category_id, category_name
        FROM categories
        ORDER BY category_id
    ");

    $categories = $statement->fetchAll();

    $response->getBody()->write(json_encode([
        'status' => 'success',
        'data' => $categories
    ]));

    return $response->withHeader(
        'Content-Type',
        'application/json'
    );
})->add(new ApiKeyMiddleware());