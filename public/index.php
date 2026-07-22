<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response as SlimResponse;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);


/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

function getDatabaseConnection(): PDO
{
    $host = 'localhost';
    $database = 'filipino_cookbook_api';
    $username = 'root';
    $password = '';

    $dsn = "mysql:host={$host};dbname={$database};charset=utf8mb4";

    return new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
}


/*
|--------------------------------------------------------------------------
| TOKEN MIDDLEWARE
|--------------------------------------------------------------------------
*/

$tokenMiddleware = function (
    Request $request,
    RequestHandler $handler
): Response {
    $authorization = $request->getHeaderLine('Authorization');

    $validToken = 'Bearer dmmmsu-cookbook-token-2026';

    if (
        $authorization === '' ||
        !hash_equals($validToken, $authorization)
    ) {
        $response = new SlimResponse();

        $response->getBody()->write(json_encode([
            'status' => 'error',
            'message' => 'Unauthorized access. Valid API token is required.'
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401);
    }

    return $handler->handle($request);
};

/*
|--------------------------------------------------------------------------
| RATE LIMIT MIDDLEWARE
|--------------------------------------------------------------------------
*/

$rateLimitMiddleware = function (
    Request $request,
    RequestHandler $handler
): Response {

    $limit = 10;      // Maximum requests
    $window = 60;     // Seconds

    $ip = $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown';

    $directory = __DIR__ . '/../storage';

    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    $file = $directory . '/' . md5($ip) . '.json';

    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
    } else {
        $data = [
            'count' => 0,
            'time' => time()
        ];
    }

    if ((time() - $data['time']) > $window) {
        $data = [
            'count' => 0,
            'time' => time()
        ];
    }

    $data['count']++;

    file_put_contents($file, json_encode($data));

    if ($data['count'] > $limit) {

        $response = new SlimResponse();

        $response->getBody()->write(json_encode([
            'status' => 'error',
            'message' => 'Too many requests. Please wait before trying again.'
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(429);
    }

    return $handler->handle($request);
};

/*
|--------------------------------------------------------------------------
| 1. PUBLIC WELCOME ROUTE
|--------------------------------------------------------------------------
*/

$app->get('/', function (
    Request $request,
    Response $response
): Response {
    $response->getBody()->write(json_encode([
        'message' => 'Welcome to the Secured Filipino Cookbook API',
        'note' => 'Use a valid Bearer token to access /api endpoints.'
    ]));

    return $response->withHeader(
        'Content-Type',
        'application/json'
    );
});


/*
|--------------------------------------------------------------------------
| SECURED API ROUTES
|--------------------------------------------------------------------------
*/

$app->group('/api', function (RouteCollectorProxy $group) {


    /*
    |--------------------------------------------------------------------------
    | 2. GET ALL FOODS
    |--------------------------------------------------------------------------
    */

    $group->get('/foods', function (
        Request $request,
        Response $response
    ): Response {
        try {
            $pdo = getDatabaseConnection();

            $statement = $pdo->query("
                SELECT
                    f.food_id,
                    f.food_name,
                    c.category_name,
                    o.origin_name,
                    f.instructions
                FROM foods f
                INNER JOIN categories c
                    ON f.category_id = c.category_id
                INNER JOIN origins o
                    ON f.origin_id = o.origin_id
                ORDER BY f.food_id
            ");

            $foods = $statement->fetchAll();

            foreach ($foods as &$food) {
                $ingredientStatement = $pdo->prepare("
                    SELECT i.ingredient_name
                    FROM ingredients i
                    INNER JOIN food_ingredients fi
                        ON i.ingredient_id = fi.ingredient_id
                    WHERE fi.food_id = :food_id
                    ORDER BY i.ingredient_name
                ");

                $ingredientStatement->execute([
                    'food_id' => $food['food_id']
                ]);

                $food['ingredients'] = $ingredientStatement->fetchAll(
                    PDO::FETCH_COLUMN
                );
            }

            unset($food);

            $response->getBody()->write(json_encode(
                $foods,
                JSON_PRETTY_PRINT
            ));

            return $response->withHeader(
                'Content-Type',
                'application/json'
            );

        } catch (PDOException $exception) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    });


    /*
    |--------------------------------------------------------------------------
    | 4. SEARCH FOOD BY NAME
    |--------------------------------------------------------------------------
    |
    | This is placed before /foods/{id}.
    |
    */

    $group->get('/foods/search/{name}', function (
        Request $request,
        Response $response,
        array $args
    ): Response {
        try {
            $pdo = getDatabaseConnection();

            $name = trim($args['name']);

            $statement = $pdo->prepare("
                SELECT
                    f.food_id,
                    f.food_name,
                    c.category_name,
                    o.origin_name,
                    f.instructions
                FROM foods f
                INNER JOIN categories c
                    ON f.category_id = c.category_id
                INNER JOIN origins o
                    ON f.origin_id = o.origin_id
                WHERE f.food_name LIKE :name
                ORDER BY f.food_name
            ");

            $statement->execute([
                'name' => '%' . $name . '%'
            ]);

            $foods = $statement->fetchAll();

            $response->getBody()->write(json_encode(
                $foods,
                JSON_PRETTY_PRINT
            ));

            return $response->withHeader(
                'Content-Type',
                'application/json'
            );

        } catch (PDOException $exception) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    });


    /*
    |--------------------------------------------------------------------------
    | 3. GET FOOD BY ID
    |--------------------------------------------------------------------------
    */

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
                    c.category_name,
                    o.origin_name,
                    f.instructions
                FROM foods f
                INNER JOIN categories c
                    ON f.category_id = c.category_id
                INNER JOIN origins o
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
                    'message' => 'Food not found'
                ]));

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(404);
            }

            $ingredientStatement = $pdo->prepare("
                SELECT i.ingredient_name
                FROM ingredients i
                INNER JOIN food_ingredients fi
                    ON i.ingredient_id = fi.ingredient_id
                WHERE fi.food_id = :food_id
                ORDER BY i.ingredient_name
            ");

            $ingredientStatement->execute([
                'food_id' => $foodId
            ]);

            $food['ingredients'] = $ingredientStatement->fetchAll(
                PDO::FETCH_COLUMN
            );

            $response->getBody()->write(json_encode(
                $food,
                JSON_PRETTY_PRINT
            ));

            return $response->withHeader(
                'Content-Type',
                'application/json'
            );

        } catch (PDOException $exception) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    });


/*
|--------------------------------------------------------------------------
| EXTRA: RANDOM FOOD
|--------------------------------------------------------------------------
*/

$group->get('/foods/random', function (
    Request $request,
    Response $response
): Response {

    try {

        $pdo = getDatabaseConnection();

        $statement = $pdo->query("
            SELECT
                f.food_id,
                f.food_name,
                c.category_name,
                o.origin_name,
                f.instructions
            FROM foods f
            INNER JOIN categories c
                ON f.category_id = c.category_id
            INNER JOIN origins o
                ON f.origin_id = o.origin_id
            ORDER BY RAND()
            LIMIT 1
        ");

        $food = $statement->fetch();

        if (!$food) {

            $response->getBody()->write(json_encode([
                "status" => "error",
                "message" => "No food found."
            ]));

            return $response
                ->withHeader("Content-Type","application/json")
                ->withStatus(404);
        }

        $ingredientStatement = $pdo->prepare("
            SELECT i.ingredient_name
            FROM ingredients i
            INNER JOIN food_ingredients fi
                ON i.ingredient_id = fi.ingredient_id
            WHERE fi.food_id = :food_id
            ORDER BY i.ingredient_name
        ");

        $ingredientStatement->execute([
            "food_id" => $food["food_id"]
        ]);

        $food["ingredients"] =
            $ingredientStatement->fetchAll(PDO::FETCH_COLUMN);

        $response->getBody()->write(
            json_encode($food, JSON_PRETTY_PRINT)
        );

        return $response->withHeader(
            "Content-Type",
            "application/json"
        );

    } catch (PDOException $exception) {

        $response->getBody()->write(json_encode([
            "status"=>"error",
            "message"=>$exception->getMessage()
        ]));

        return $response
            ->withHeader("Content-Type","application/json")
            ->withStatus(500);
    }
});

    /*
    |--------------------------------------------------------------------------
    | 5. GET ALL CATEGORIES
    |--------------------------------------------------------------------------
    */

    $group->get('/categories', function (
        Request $request,
        Response $response
    ): Response {
        try {
            $pdo = getDatabaseConnection();

            $statement = $pdo->query("
                SELECT
                    category_id,
                    category_name
                FROM categories
                ORDER BY category_id
            ");

            $categories = $statement->fetchAll();

            $response->getBody()->write(json_encode(
                $categories,
                JSON_PRETTY_PRINT
            ));

            return $response->withHeader(
                'Content-Type',
                'application/json'
            );

        } catch (PDOException $exception) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    });


    /*
    |--------------------------------------------------------------------------
    | 6. GET ALL INGREDIENTS
    |--------------------------------------------------------------------------
    */

    $group->get('/ingredients', function (
        Request $request,
        Response $response
    ): Response {
        try {
            $pdo = getDatabaseConnection();

            $statement = $pdo->query("
                SELECT
                    ingredient_id,
                    ingredient_name
                FROM ingredients
                ORDER BY ingredient_name
            ");

            $ingredients = $statement->fetchAll();

            $response->getBody()->write(json_encode(
                $ingredients,
                JSON_PRETTY_PRINT
            ));

            return $response->withHeader(
                'Content-Type',
                'application/json'
            );

        } catch (PDOException $exception) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    });


    /*
    |--------------------------------------------------------------------------
    | 7. ADD NEW FOOD
    |--------------------------------------------------------------------------
    */

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
                $instructions === '' ||
                !is_array($ingredientIds)
            ) {
                $response->getBody()->write(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid or incomplete food data.'
                ]));

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(422);
            }

            $pdo = getDatabaseConnection();

            $pdo->beginTransaction();

            $idStatement = $pdo->query("
                SELECT COALESCE(MAX(food_id), 0) + 1
                FROM foods
            ");

            $foodId = (int) $idStatement->fetchColumn();

            $foodStatement = $pdo->prepare("
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

            $foodStatement->execute([
                'food_id' => $foodId,
                'food_name' => $foodName,
                'category_id' => $categoryId,
                'origin_id' => $originId,
                'instructions' => $instructions
            ]);

            $ingredientStatement = $pdo->prepare("
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
                $ingredientStatement->execute([
                    'food_id' => $foodId,
                    'ingredient_id' => (int) $ingredientId
                ]);
            }

            $pdo->commit();

            $response->getBody()->write(json_encode([
                'status' => 'success',
                'message' => 'Food added successfully.'
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);

        } catch (PDOException $exception) {
            if (
                $pdo instanceof PDO &&
                $pdo->inTransaction()
            ) {
                $pdo->rollBack();
            }

            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    });

        /*
    |--------------------------------------------------------------------------
    | EXTRA: UPDATE FOOD
    |--------------------------------------------------------------------------
    */

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
                $instructions === '' ||
                !is_array($ingredientIds)
            ) {
                $response->getBody()->write(json_encode([
                    'status' => 'error',
                    'message' => 'Invalid or incomplete food data.'
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
                    'message' => 'Food not found'
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

            $ingredientStatement = $pdo->prepare("
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
                $ingredientStatement->execute([
                    'food_id' => $foodId,
                    'ingredient_id' => (int) $ingredientId
                ]);
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
            if (
                $pdo instanceof PDO &&
                $pdo->inTransaction()
            ) {
                $pdo->rollBack();
            }

            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    });

        /*
    |--------------------------------------------------------------------------
    | EXTRA: DELETE FOOD
    |--------------------------------------------------------------------------
    */

    $group->delete('/foods/{id:[0-9]+}', function (
        Request $request,
        Response $response,
        array $args
    ): Response {
        $pdo = null;

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
                    'message' => 'Food not found'
                ]));

                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(404);
            }

            $pdo->beginTransaction();

            $deleteIngredients = $pdo->prepare("
                DELETE FROM food_ingredients
                WHERE food_id = :food_id
            ");

            $deleteIngredients->execute([
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
                'message' => 'Food deleted successfully.'
            ]));

            return $response->withHeader(
                'Content-Type',
                'application/json'
            );

        } catch (PDOException $exception) {
            if (
                $pdo instanceof PDO &&
                $pdo->inTransaction()
            ) {
                $pdo->rollBack();
            }

            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    });

})

->add($tokenMiddleware)
->add($rateLimitMiddleware);

$app->run();