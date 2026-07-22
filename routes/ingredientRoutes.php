<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/api/ingredients', function (
    Request $request,
    Response $response
): Response {
    $pdo = getDatabaseConnection();

    $statement = $pdo->query("
        SELECT ingredient_id, ingredient_name
        FROM ingredients
        ORDER BY ingredient_name
    ");

    $ingredients = $statement->fetchAll();

    $response->getBody()->write(json_encode([
        'status' => 'success',
        'data' => $ingredients
    ]));

    return $response->withHeader(
        'Content-Type',
        'application/json'
    );
})->add(new ApiKeyMiddleware());