<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as SlimResponse;

class ApiKeyMiddleware
{
    private string $validToken = 'dmmmsu-cookbook-token-2026';

    public function __invoke(
        Request $request,
        RequestHandler $handler
    ): Response {
        $authorization = $request->getHeaderLine('Authorization');
        $expectedAuthorization = 'Bearer ' . $this->validToken;

        if (
            $authorization === '' ||
            !hash_equals($expectedAuthorization, $authorization)
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
    }
}