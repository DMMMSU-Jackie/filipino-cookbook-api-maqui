<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/api/origins', function (
    Request $request,
    Response $response
): Response {
    $pdo = getDatabaseConnection();

    $statement = $pdo->query("
        SELECT origin_id, origin_name
        FROM origins
        ORDER BY origin_id
    ");

    $origins = $statement->fetchAll();

    $response->getBody()->write(json_encode([
        'status' => 'success',
        'data' => $origins
    ]));

    return $response->withHeader(
        'Content-Type',
        'application/json'
    );
})->add(new ApiKeyMiddleware());