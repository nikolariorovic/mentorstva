<?php

namespace App\Core;

use Closure;

final class Router
{
    public function __construct(private readonly Container $container)
    {

    }

    /**
     * @var array<string, mixed>
     */
    private array $routes = [];
    /**
     * @var callable|null
     */
    private $notFoundHandler;
    /**
     * @var array<int, callable>
     */
    private array $currentGroupMiddleware = [];
    private string $currentGroupPrefix = '';

    /**
     * @param callable|string|array<string|object, string> $handler
     * @param array<int, callable|string> $middleware
     */
    public function get(string $uri, array|callable|string $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $uri, $handler, $middleware);
    }

    /**
     * @param callable|string|array<string|object, string> $handler
     * @param array<int, callable|string> $middleware
     */
    public function post(string $uri, array|callable|string $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $uri, $handler, $middleware);
    }

    /**
     * @param callable|string|array<string|object, string> $handler
     * @param array<int, callable|string> $middleware
     */
    public function patch(string $uri, array|callable|string $handler, array $middleware = []): void
    {
        $this->addRoute('PATCH', $uri, $handler, $middleware);
    }

    /**
     * @param callable|string|array<string|object, string> $handler
     * @param array<int, callable|string> $middleware
     */
    public function delete(string $uri, array|callable|string $handler, array $middleware = []): void
    {
        $this->addRoute('DELETE', $uri, $handler, $middleware);
    }

    /**
     * @param callable|string|array<string|object, string> $handler
     * @param array<int, callable|string> $middleware
     */
    private function addRoute(string $method, string $uri, array|callable|string $handler, array $middleware = []): void
    {
        $middleware = array_merge($this->currentGroupMiddleware, $middleware);
        $uri = $this->currentGroupPrefix . $uri;

        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = rtrim($uri, '/');
        }
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    /**
     * @param array{middleware?: array<int, callable|string>, prefix?: string} $options
     * @param Closure $callback
     */
    public function group(array $options, Closure $callback): void
    {
        $parentMiddleware = $this->currentGroupMiddleware;
        $parentPrefix = $this->currentGroupPrefix;

        $this->currentGroupMiddleware = array_merge($parentMiddleware, $options['middleware'] ?? []);
        $this->currentGroupPrefix = $parentPrefix . ($options['prefix'] ?? '');

        $callback($this);

        $this->currentGroupMiddleware = $parentMiddleware;
        $this->currentGroupPrefix = $parentPrefix;
    }

    /**
     * @param callable $handler
     */
    public function setNotFoundHandler(callable $handler): void
    {
        $this->notFoundHandler = $handler;
    }

    public function dispatch(): mixed
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if ($requestUri !== '/' && substr($requestUri, -1) === '/') {
            $requestUri = rtrim($requestUri, '/');
        }

        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            $allowedMethods = ['PUT', 'PATCH', 'DELETE'];
            $methodFromForm = strtoupper($_POST['_method']);
            if (in_array($methodFromForm, $allowedMethods, true)) {
                $requestMethod = $methodFromForm;
            }
        }

        foreach ($this->routes as $route) {
            $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([a-zA-Z0-9_-]+)', $route['uri']);
            $pattern = "#^" . $pattern . "$#";

            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);

                foreach ($route['middleware'] as $middleware) {
                    $result = (new $middleware())->handle();
                    if ($result === false) {
                        return null;
                    }
                }

                if (is_array($route['handler'])) {
                    [$class, $method] = $route['handler'];
                    $controller = $this->container->resolve($class);
                    return call_user_func_array([$controller, $method], $matches);
                } else {
                    return call_user_func_array($route['handler'], $matches);
                }
            }
        }

        if ($this->notFoundHandler) {
            call_user_func($this->notFoundHandler);
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
        return null;
    }
} 