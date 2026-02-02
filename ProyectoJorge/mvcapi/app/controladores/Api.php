<?php

namespace Cls\Mvc2app;

use Cls\Mvc2app\Controlador;

class Api extends Controlador
{
    public function __construct(){
        $this->modelo = $this->modelo('car');
    }

    private function jsonResponse($data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    private function readJsonBody(): ?array
    {
        $raw = file_get_contents("php://input");
        if ($raw === false || trim($raw) === '') {
            return null;
        }
        $data = json_decode($raw, true);
        return is_array($data) ? $data : null;
    }

    private function getRequestMethod(): string
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if (strtoupper($method) === 'POST') {
            $override = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ?? null;
            if (!$override && isset($_POST['_method'])) {
                $override = $_POST['_method'];
            }
            if ($override) {
                $method = $override;
            }
        }

        return strtoupper($method);
    }

    private function getAuthorizationHeader(): ?string
    {
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            return $_SERVER['HTTP_AUTHORIZATION'];
        }
        if (!empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            return $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            if (isset($headers['Authorization'])) {
                return $headers['Authorization'];
            }
            if (isset($headers['authorization'])) {
                return $headers['authorization'];
            }
        }
        return null;
    }

    private function parseBasicAuthFromHeader(?string $header): array
    {
        if (!$header) return [null, null];

        if (stripos($header, 'Basic ') !== 0) {
            return [null, null];
        }

        $payload = trim(substr($header, 6));

        // Permitir formato no estándar: "Basic usuario contraseña"
        if (strpos($payload, ' ') !== false) {
            [$u, $p] = explode(' ', $payload, 2);
            return [$u ?: null, $p ?: null];
        }

        $decoded = base64_decode($payload);
        if (!$decoded || strpos($decoded, ':') === false) {
            return [null, null];
        }

        return explode(':', $decoded, 2);
    }

    private function requireBasicAuth(): void
    {
        $user = $_SERVER['PHP_AUTH_USER'] ?? null;
        $pass = $_SERVER['PHP_AUTH_PW'] ?? null;

        if ($user === null || $pass === null) {
            [$u, $p] = $this->parseBasicAuthFromHeader($this->getAuthorizationHeader());
            $user = $user ?? $u;
            $pass = $pass ?? $p;
        }

        if ($user !== API_BASIC_USER || $pass !== API_BASIC_PASS) {
            header('WWW-Authenticate: Basic realm="mvcapi"');
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }
    }

    // GET /api/debug
    public function debug(): void
    {
        $this->jsonResponse([
            'message' => 'Debug de la petición HTTP recibida por la API',
            'request' => $this->debugRequest()
        ], 200);
    }

    private function debugRequest(): array
    {
        return [
            'method' => $this->getRequestMethod(),
            'uri' => $_SERVER['REQUEST_URI'] ?? null,
            'query' => $_GET ?? [],
            'headers' => function_exists('getallheaders') ? getallheaders() : [],
            'body' => $this->readJsonBody(),
            'server' => [
                'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? null,
                'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            ],
        ];
    }

    private function validateCarPayload(?array $data, bool $requireAllFields = true): ?string
    {
        if (!$data) return "Invalid or empty JSON";

        $required = ['brand','model','color','owner'];
        if ($requireAllFields) {
            foreach ($required as $k) {
                if (!isset($data[$k]) || trim((string)$data[$k]) === '') {
                    return "Missing or empty field: $k";
                }
            }
        }
        return null;
    }

    private function validateArticuloPayload(?array $data, bool $requireAllFields = true): ?string
    {
        if (!$data) return "Invalid or empty JSON";

        $required = ['titulo','descripcion','foto','cosa'];
        if ($requireAllFields) {
            foreach ($required as $k) {
                if (!isset($data[$k]) || trim((string)$data[$k]) === '') {
                    return "Missing or empty field: $k";
                }
            }
        }
        return null;
    }

    private function validateUsuarioPayload(?array $data, bool $requireAllFields = true): ?string
    {
        if (!$data) return "Invalid or empty JSON";

        $required = ['nombre','clave'];
        if ($requireAllFields) {
            foreach ($required as $k) {
                if (!isset($data[$k]) || trim((string)$data[$k]) === '') {
                    return "Missing or empty field: $k";
                }
            }
        }
        return null;
    }

    // GET /api/cars  |  POST /api/cars
    public function cars(): void
    {
        $this->requireBasicAuth();

        $method = $this->getRequestMethod();

        if ($method === 'GET') {
            $cars = $this->modelo->getAll();
            $this->jsonResponse($cars, 200);
        }

        if ($method === 'POST') {
            $data = $this->readJsonBody();
            $err = $this->validateCarPayload($data, true);
            if ($err) {
                $this->jsonResponse(["error" => $err], 400);
            }

            $ok = $this->modelo->create($data);
            if ($ok) {
                $this->jsonResponse(["message" => "Car created"], 201);
            }
            $this->jsonResponse(["error" => "Error creating car"], 500);
        }

        $this->jsonResponse(["error" => "Method Not Allowed"], 405);
    }

    // GET /api/car/1 | PUT /api/car/1 | DELETE /api/car/1
    public function car(int $id): void
    {
        $this->requireBasicAuth();

        $method = $this->getRequestMethod();

        if ($id <= 0) {
            $this->jsonResponse(["error" => "Invalid id"], 400);
        }

        if ($method === 'GET') {
            $car = $this->modelo->getById($id);
            if (!$car) {
                $this->jsonResponse(["error" => "Car not found"], 404);
            }
            $this->jsonResponse($car, 200);
        }

        if ($method === 'PUT') {
            $data = $this->readJsonBody();
            $err = $this->validateCarPayload($data, true);
            if ($err) {
                $this->jsonResponse(["error" => $err], 400);
            }

            // opcional: comprobar existencia antes
            $exists = $this->modelo->getById($id);
            if (!$exists) {
                $this->jsonResponse(["error" => "Car not found"], 404);
            }

            $ok = $this->modelo->update($id, $data);
            if ($ok) {
                $this->jsonResponse(["message" => "Car updated"], 200);
            }
            $this->jsonResponse(["error" => "Error updating car"], 500);
        }

        if ($method === 'DELETE') {
            // opcional: comprobar existencia antes
            $exists = $this->modelo->getById($id);
            if (!$exists) {
                $this->jsonResponse(["error" => "Car not found"], 404);
            }

            $ok = $this->modelo->delete($id);
            if ($ok) {
                $this->jsonResponse(["message" => "Car deleted"], 200);
            }
            $this->jsonResponse(["error" => "Error deleting car"], 500);
        }

        $this->jsonResponse(["error" => "Method Not Allowed"], 405);
    }

    // GET /api/articulos  |  POST /api/articulos
    public function articulos(): void
    {
        $this->requireBasicAuth();

        $method = $this->getRequestMethod();
        $articuloModelo = $this->modelo('articulo');

        if ($method === 'GET') {
            $articulos = $articuloModelo->obtenerArticulos();
            $this->jsonResponse($articulos, 200);
        }

        if ($method === 'POST') {
            $data = $this->readJsonBody();
            $err = $this->validateArticuloPayload($data, true);
            if ($err) {
                $this->jsonResponse(["error" => $err], 400);
            }

            $ok = $articuloModelo->create($data);
            if ($ok) {
                $this->jsonResponse(["message" => "Articulo created"], 201);
            }
            $this->jsonResponse(["error" => "Error creating articulo"], 500);
        }

        $this->jsonResponse(["error" => "Method Not Allowed"], 405);
    }

    // GET /api/articulo/1 | PUT /api/articulo/1 | DELETE /api/articulo/1
    public function articulo(int $id): void
    {
        $this->requireBasicAuth();

        $method = $this->getRequestMethod();
        $articuloModelo = $this->modelo('articulo');

        if ($id <= 0) {
            $this->jsonResponse(["error" => "Invalid id"], 400);
        }

        if ($method === 'GET') {
            $articulo = $articuloModelo->obtenerArticulo($id);
            if (!$articulo) {
                $this->jsonResponse(["error" => "Articulo not found"], 404);
            }
            $this->jsonResponse($articulo, 200);
        }

        if ($method === 'PUT') {
            $data = $this->readJsonBody();
            $err = $this->validateArticuloPayload($data, true);
            if ($err) {
                $this->jsonResponse(["error" => $err], 400);
            }

            $exists = $articuloModelo->obtenerArticulo($id);
            if (!$exists) {
                $this->jsonResponse(["error" => "Articulo not found"], 404);
            }

            $ok = $articuloModelo->update($id, $data);
            if ($ok) {
                $this->jsonResponse(["message" => "Articulo updated"], 200);
            }
            $this->jsonResponse(["error" => "Error updating articulo"], 500);
        }

        if ($method === 'DELETE') {
            $exists = $articuloModelo->obtenerArticulo($id);
            if (!$exists) {
                $this->jsonResponse(["error" => "Articulo not found"], 404);
            }

            $ok = $articuloModelo->delete($id);
            if ($ok) {
                $this->jsonResponse(["message" => "Articulo deleted"], 200);
            }
            $this->jsonResponse(["error" => "Error deleting articulo"], 500);
        }

        $this->jsonResponse(["error" => "Method Not Allowed"], 405);
    }

    // GET /api/usuarios  |  POST /api/usuarios
    public function usuarios(): void
    {
        $this->requireBasicAuth();

        $method = $this->getRequestMethod();
        $usuarioModelo = $this->modelo('user');

        if ($method === 'GET') {
            $usuarios = $usuarioModelo->obtenerUsuarios();
            $this->jsonResponse($usuarios, 200);
        }

        if ($method === 'POST') {
            $data = $this->readJsonBody();
            $err = $this->validateUsuarioPayload($data, true);
            if ($err) {
                $this->jsonResponse(["error" => $err], 400);
            }

            $ok = $usuarioModelo->create($data);
            if ($ok) {
                $this->jsonResponse(["message" => "usuario created"], 201);
            }
            $this->jsonResponse(["error" => "Error creating usuario"], 500);
        }

        $this->jsonResponse(["error" => "Method Not Allowed"], 405);
    }

    // GET /api/usuario/1 | PUT /api/usuario/1 | DELETE /api/usuario/1
    public function usuario(int $id): void
    {
        $this->requireBasicAuth();

        $method = $this->getRequestMethod();
        $usuarioModelo = $this->modelo('user');

        if ($id <= 0) {
            $this->jsonResponse(["error" => "Invalid id"], 400);
        }

        if ($method === 'GET') {
            $usuario = $usuarioModelo->obtenerUsuario($id);
            if (!$usuario) {
                $this->jsonResponse(["error" => "usuario not found"], 404);
            }
            $this->jsonResponse($usuario, 200);
        }

        if ($method === 'PUT') {
            $data = $this->readJsonBody();
            $err = $this->validateUsuarioPayload($data, true);
            if ($err) {
                $this->jsonResponse(["error" => $err], 400);
            }

            $exists = $usuarioModelo->obtenerUsuario($id);
            if (!$exists) {
                $this->jsonResponse(["error" => "usuario not found"], 404);
            }

            $ok = $usuarioModelo->update($id, $data);
            if ($ok) {
                $this->jsonResponse(["message" => "usuario updated"], 200);
            }
            $this->jsonResponse(["error" => "Error updating usuario"], 500);
        }

        if ($method === 'DELETE') {
            $exists = $usuarioModelo->obtenerUsuario($id);
            if (!$exists) {
                $this->jsonResponse(["error" => "usuario not found"], 404);
            }

            $ok = $usuarioModelo->delete($id);
            if ($ok) {
                $this->jsonResponse(["message" => "usuario deleted"], 200);
            }
            $this->jsonResponse(["error" => "Error deleting usuario"], 500);
        }

        $this->jsonResponse(["error" => "Method Not Allowed"], 405);
    }

    // POST /api/login
    public function login(): void
    {
        $this->requireBasicAuth();
        $method = $this->getRequestMethod();

        if($method !== 'POST'){
            $this->jsonResponse(["error" => "Method Not Allowed"], 405);
        }
        
        $data = $this->readJsonBody();
        $err = $this->validateUsuarioPayload($data, true);
        if ($err) {
            $this->jsonResponse(["error" => $err], 400);
        }

        $usuarioModelo = $this->modelo('user');
        $usuario = $usuarioModelo->loguearUser($data['nombre'], $data['clave']);
        if (!$usuario) {
            $this->jsonResponse(["error" => "Invalid credentials"], 401);
        }

        $this->jsonResponse(["message" => "Login successful", "user" => $usuario], 200);
    }
}