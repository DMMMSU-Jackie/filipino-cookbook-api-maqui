<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;

$app->group('/api', function (RouteCollectorProxy $group) {

    $group->get('/foods', function (
        Request $request,
        Response $response
    ): Response {
        try {
            $pdo = getDatabaseConnection();

            $sql = "
                SELECT
                    f.food_id,
                    f.food_name,
                    f.instructions,
                    c.category_name,
                    o.origin_name
                FROM foods AS f
                INNER JOIN categories AS c
                    ON f.category_id = c.category_id
                INNER JOIN origins AS o
                    ON f.origin_id = o.origin_id
                ORDER BY f.food_id
            ";

            $statement = $pdo->query($sql);
            $foods = $statement->fetchAll();

            $response->getBody()->write(json_encode([
                'status' => 'success',
                'count' => count($foods),
                'data' => $foods
            ]));

            return $response->withHeader(
                'Content-Type',
                'application/json'
            );
        } catch (PDOException $exception) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Unable to retrieve foods.',
                'error' => $exception->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    });

    $group->get('/foods/search', function (
    Request $request,
    Response $response
): Response {
    try {
        $queryParams = $request->getQueryParams();
        $name = trim($queryParams['name'] ?? '');

        if ($name === '') {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'The name query parameter is required.'
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(422);
        }

        $pdo = getDatabaseConnection();

        $statement = $pdo->prepare("
            SELECT
                f.food_id,
                f.food_name,
                f.instructions,
                c.category_name,
                o.origin_name
            FROM foods AS f
            INNER JOIN categories AS c
                ON f.category_id = c.category_id
            INNER JOIN origins AS o
                ON f.origin_id = o.origin_id
            WHERE f.food_name LIKE :food_name
            ORDER BY f.food_name
        ");

        $statement->execute([
            'food_name' => '%' . $name . '%'
        ]);

        $foods = $statement->fetchAll();

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'search_term' => $name,
            'count' => count($foods),
            'data' => $foods
        ]));

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );

    } catch (PDOException $exception) {
        $response->getBody()->write(json_encode([
            'status' => 'error',
            'message' => 'Unable to search for foods.',
            'error' => $exception->getMessage()
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }
});

$group->get('/foods/random', function (
    Request $request,
    Response $response
): Response {

    try {

        $pdo = getDatabaseConnection();

        $sql = "
            SELECT
                f.food_id,
                f.food_name,
                f.instructions,
                c.category_name,
                o.origin_name
            FROM foods f
            INNER JOIN categories c
                ON f.category_id = c.category_id
            INNER JOIN origins o
                ON f.origin_id = o.origin_id
            ORDER BY RAND()
            LIMIT 1
        ";

        $statement = $pdo->query($sql);
        $food = $statement->fetch();

        $response->getBody()->write(json_encode([
            "status" => "success",
            "data" => $food
        ]));

        return $response->withHeader("Content-Type", "application/json");

    } catch (PDOException $e) {

        $response->getBody()->write(json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]));

        return $response
            ->withHeader("Content-Type", "application/json")
            ->withStatus(500);
    }
});

    $group->get('/foods/{id:[0-9]+}', function (
        Request $request,
        Response $response,
        array $args
    ): Response {
        try {
            $pdo = getDatabaseConnection();
            $foodId = (int) $args['id'];

            $statement = $pdo->prepare("
                SELECT
                    f.food_id,
                    f.food_name,
                    f.instructions,
                    c.category_name,
                    o.origin_name
                FROM foods AS f
                INNER JOIN categories AS c
                    ON f.category_id = c.category_id
                INNER JOIN origins AS o
                    ON f.origin_id = o.origin_id
                WHERE f.food_id = :food_id
            ");

            $statement->execute([
                'food_id' => $foodId
            ]);

            $food = $statement->fetch();

            if (!$food) {
                $response->getBody()->write(json_encode([
                    'status' => 'error',
                    'message' => 'Food not found.'
                ]));

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(404);
            }

            $response->getBody()->write(json_encode([
                'status' => 'success',
                'data' => $food
            ]));

            return $response->withHeader(
                'Content-Type',
                'application/json'
            );
        } catch (PDOException $exception) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Unable to retrieve the food.',
                'error' => $exception->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    });

        $group->post('/foods', function (
        Request $request,
        Response $response
    ): Response {
        $pdo = null;

        try {
            $data = $request->getParsedBody();

            $foodName = trim($data['food_name'] ?? '');
            $categoryId = (int) ($data['category_id'] ?? 0);
            $originId = (int) ($data['origin_id'] ?? 0);
            $instructions = trim($data['instructions'] ?? '');
            $ingredientIds = $data['ingredient_ids'] ?? [];

            if (
                $foodName === '' ||
                $categoryId <= 0 ||
                $originId <= 0 ||
                $instructions === ''
            ) {
                $response->getBody()->write(json_encode([
                    'status' => 'error',
                    'message' => 'food_name, category_id, origin_id, and instructions are required.'
                ]));

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(422);
            }

            if (!is_array($ingredientIds)) {
                $response->getBody()->write(json_encode([
                    'status' => 'error',
                    'message' => 'ingredient_ids must be an array.'
                ]));

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(422);
            }

            $pdo = getDatabaseConnection();
            $pdo->beginTransaction();

            /*
             * The uploaded database uses manually assigned IDs,
             * so the next available food ID is generated here.
             */
            $idStatement = $pdo->query("
                SELECT COALESCE(MAX(food_id), 0) + 1 AS next_id
                FROM foods
            ");

            $foodId = (int) $idStatement->fetch()['next_id'];

            $insertFood = $pdo->prepare("
                INSERT INTO foods (
                    food_id,
                    food_name,
                    category_id,
                    origin_id,
                    instructions
                )
                VALUES (
                    :food_id,
                    :food_name,
                    :category_id,
                    :origin_id,
                    :instructions
                )
            ");

            $insertFood->execute([
                'food_id' => $foodId,
                'food_name' => $foodName,
                'category_id' => $categoryId,
                'origin_id' => $originId,
                'instructions' => $instructions
            ]);

            if (count($ingredientIds) > 0) {
                $insertIngredient = $pdo->prepare("
                    INSERT INTO food_ingredients (
                        food_id,
                        ingredient_id
                    )
                    VALUES (
                        :food_id,
                        :ingredient_id
                    )
                ");

                foreach (array_unique($ingredientIds) as $ingredientId) {
                    $insertIngredient->execute([
                        'food_id' => $foodId,
                        'ingredient_id' => (int) $ingredientId
                    ]);
                }
            }

            $pdo->commit();

            $response->getBody()->write(json_encode([
                'status' => 'success',
                'message' => 'Food created successfully.',
                'food_id' => $foodId
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);

        } catch (PDOException $exception) {
            if ($pdo instanceof PDO && $pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Unable to create food.',
                'error' => $exception->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    });

        $group->put('/foods/{id:[0-9]+}', function (
        Request $request,
        Response $response,
        array $args
    ): Response {
        $pdo = null;

        try {
            $foodId = (int) $args['id'];
            $data = $request->getParsedBody();

            $foodName = trim($data['food_name'] ?? '');
            $categoryId = (int) ($data['category_id'] ?? 0);
            $originId = (int) ($data['origin_id'] ?? 0);
            $instructions = trim($data['instructions'] ?? '');
            $ingredientIds = $data['ingredient_ids'] ?? [];

            if (
                $foodName === '' ||
                $categoryId <= 0 ||
                $originId <= 0 ||
                $instructions === ''
            ) {
                $response->getBody()->write(json_encode([
                    'status' => 'error',
                    'message' => 'food_name, category_id, origin_id, and instructions are required.'
                ]));

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(422);
            }

            if (!is_array($ingredientIds)) {
                $response->getBody()->write(json_encode([
                    'status' => 'error',
                    'message' => 'ingredient_ids must be an array.'
                ]));

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(422);
            }

            $pdo = getDatabaseConnection();

            $checkStatement = $pdo->prepare("
                SELECT food_id
                FROM foods
                WHERE food_id = :food_id
            ");

            $checkStatement->execute([
                'food_id' => $foodId
            ]);

            if (!$checkStatement->fetch()) {
                $response->getBody()->write(json_encode([
                    'status' => 'error',
                    'message' => 'Food not found.'
                ]));

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(404);
            }

            $pdo->beginTransaction();

            $updateStatement = $pdo->prepare("
                UPDATE foods
                SET
                    food_name = :food_name,
                    category_id = :category_id,
                    origin_id = :origin_id,
                    instructions = :instructions
                WHERE food_id = :food_id
            ");

            $updateStatement->execute([
                'food_name' => $foodName,
                'category_id' => $categoryId,
                'origin_id' => $originId,
                'instructions' => $instructions,
                'food_id' => $foodId
            ]);

            $deleteIngredients = $pdo->prepare("
                DELETE FROM food_ingredients
                WHERE food_id = :food_id
            ");

            $deleteIngredients->execute([
                'food_id' => $foodId
            ]);

            if (count($ingredientIds) > 0) {
                $insertIngredient = $pdo->prepare("
                    INSERT INTO food_ingredients (
                        food_id,
                        ingredient_id
                    )
                    VALUES (
                        :food_id,
                        :ingredient_id
                    )
                ");

                foreach (array_unique($ingredientIds) as $ingredientId) {
                    $insertIngredient->execute([
                        'food_id' => $foodId,
                        'ingredient_id' => (int) $ingredientId
                    ]);
                }
            }

            $pdo->commit();

            $response->getBody()->write(json_encode([
                'status' => 'success',
                'message' => 'Food updated successfully.'
            ]));

            return $response->withHeader(
                'Content-Type',
                'application/json'
            );

        } catch (PDOException $exception) {
            if ($pdo instanceof PDO && $pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Unable to update food.',
                'error' => $exception->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    });

        $group->delete('/foods/{id:[0-9]+}', function (
        Request $request,
        Response $response,
        array $args
    ): Response {
        try {
            $foodId = (int) $args['id'];
            $pdo = getDatabaseConnection();

            $checkStatement = $pdo->prepare("
                SELECT food_id, food_name
                FROM foods
                WHERE food_id = :food_id
            ");

            $checkStatement->execute([
                'food_id' => $foodId
            ]);

            $food = $checkStatement->fetch();

            if (!$food) {
                $response->getBody()->write(json_encode([
                    'status' => 'error',
                    'message' => 'Food not found.'
                ]));

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(404);
            }

            $pdo->beginTransaction();

            /*
             * Delete the relationships first to avoid
             * foreign-key constraint errors.
             */
            $deleteRelationships = $pdo->prepare("
                DELETE FROM food_ingredients
                WHERE food_id = :food_id
            ");

            $deleteRelationships->execute([
                'food_id' => $foodId
            ]);

            $deleteFood = $pdo->prepare("
                DELETE FROM foods
                WHERE food_id = :food_id
            ");

            $deleteFood->execute([
                'food_id' => $foodId
            ]);

            $pdo->commit();

            $response->getBody()->write(json_encode([
                'status' => 'success',
                'message' => $food['food_name'] . ' was deleted successfully.'
            ]));

            return $response->withHeader(
                'Content-Type',
                'application/json'
            );

        } catch (PDOException $exception) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Unable to delete food.',
                'error' => $exception->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    });



})->add(new ApiKeyMiddleware());