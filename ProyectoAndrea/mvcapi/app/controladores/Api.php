<?php

namespace Cls\Mvc2app;

use Cls\Mvc2app\Controlador;

class Api extends Controlador
{
    // Propiedades para los modelos (importante declararlas para que no de error en PHP 8.2)
    private $modeloArticulo;
    private $modeloCoche;

    public function __construct()
    {
        // Cargo los modelos que voy a usar en toda la API
        $this->modeloCoche = $this->modelo('Car');
        $this->modeloArticulo = $this->modelo('Articulo');
    }

    // Función para responder siempre en JSON y cortar la ejecución
    private function jsonResponse($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit; // Pongo el exit para asegurarme de que no se envíe nada más después
    }

    // Para leer lo que viene por POST/PUT (el body en crudo)
    private function readJsonBody(): ?array
    {
        $raw = file_get_contents("php://input");
        if ($raw === false || trim($raw) === '') {
            return null;
        }
        $data = json_decode($raw, true); // Lo paso a array asociativo
        return is_array($data) ? $data : null;
    }

    // Aquí gestiono la seguridad. He dejado lo de las constantes por si el profe pregunta
    private function requireBasicAuth(): void
    {
        /* --- ESTO ES LO DE ANTES (POR FICHERO) ---
        $user = $_SERVER['PHP_AUTH_USER'] ?? null;
        $pass = $_SERVER['PHP_AUTH_PW'] ?? null;

        if ($user !== API_BASIC_USER || $pass !== API_BASIC_PASS) {
            header('WWW-Authenticate: Basic realm="mvcapi"');
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
        }
        ------------------------------------------ */

        // --- SEGURIDAD POR BD (LO QUE PIDE EL PROFE) ---
        $userHeader = $_SERVER['PHP_AUTH_USER'] ?? null;
        $passHeader = $_SERVER['PHP_AUTH_PW'] ?? null;

        if ($userHeader && $passHeader) {
            // Busco el usuario en la tabla usando el modelo
            $modeloUser = $this->modelo('Usuario');
            $usuarioBD = $modeloUser->obtenerPorNombre($userHeader);

            /* // Si el profe no quiere hash, usaría esta comparación:
            if ($usuarioBD && $passHeader === $usuarioBD['pass']) {
                return;
            }
            */

            // Comparo la pass que me llega con el hash de la BD (Super seguro)
            if ($usuarioBD && password_verify($passHeader, $usuarioBD['pass'])) {
                return; // Si coincide, le dejo pasar
            }
        }

        // Si llego aquí es que ha fallado algo, así que le echo
        header('WWW-Authenticate: Basic realm="mvcapi"');
        $this->jsonResponse(['error' => 'Acceso denegado: Credenciales de BD incorrectas'], 401);
    }

    // Endpoint para ver qué me está llegando (útil para debuguear con Postman)
    public function debug(): void
    {
        $this->jsonResponse([
            'message' => 'Debug de la petición HTTP recibida por la API',
            'request' => $this->debugRequest()
        ], 200);
    }

    // Validación de campos para no meter basura en la BD de coches
    private function validateCarPayload(?array $data, bool $requireAllFields = true): ?string
    {
        if (!$data) return "Invalid or empty JSON";

        $required = ['brand', 'model', 'color', 'owner'];
        if ($requireAllFields) {
            foreach ($required as $k) {
                if (!isset($data[$k]) || trim((string)$data[$k]) === '') {
                    return "Missing or empty field: $k";
                }
            }
        }
        return null;
    }

    // --- RECURSO COCHES ---

    // GET para listar o POST para crear
    public function cars(): void
    {
        $this->requireBasicAuth();
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method === 'GET') {
            $cars = $this->modeloCoche->getAll();
            $this->jsonResponse($cars, 200);
        }

        if ($method === 'POST') {
            $data = $this->readJsonBody();
            $err = $this->validateCarPayload($data, true);
            if ($err) {
                $this->jsonResponse(["error" => $err], 400);
            }

            if ($this->modeloCoche->create($data)) {
                $this->jsonResponse(["message" => "Car created"], 201);
            }
            $this->jsonResponse(["error" => "Error creating car"], 500);
        }

        $this->jsonResponse(["error" => "Method Not Allowed"], 405);
    }

    // Operaciones con un coche específico (id)
    public function car(int $id): void
    {
        $this->requireBasicAuth();
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($id <= 0) $this->jsonResponse(["error" => "Invalid id"], 400);

        // Ver si el coche existe antes de hacer nada
        $exists = $this->modeloCoche->getById($id);
        if (!$exists) $this->jsonResponse(["error" => "Car not found"], 404);

        if ($method === 'GET') {
            $this->jsonResponse($exists, 200);
        }

        if ($method === 'PUT') {
            $data = $this->readJsonBody();
            $err = $this->validateCarPayload($data, true);
            if ($err) $this->jsonResponse(["error" => $err], 400);

            if ($this->modeloCoche->update($id, $data)) {
                $this->jsonResponse(["message" => "Car updated"], 200);
            }
            $this->jsonResponse(["error" => "Error updating car"], 500);
        }

        if ($method === 'DELETE') {
            if ($this->modeloCoche->delete($id)) {
                $this->jsonResponse(["message" => "Car deleted"], 200);
            }
            $this->jsonResponse(["error" => "Error deleting car"], 500);
        }

        $this->jsonResponse(["error" => "Method Not Allowed"], 405);
    }

    // --- RECURSO ARTICULOS ---

    // Listado y creación de artículos
    public function articulos(): void
    {
        $this->requireBasicAuth();
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method === 'GET') {
            $articulos = $this->modeloArticulo->getAll();
            $this->jsonResponse($articulos, 200);
        }

        if ($method === 'POST') {
            $data = $this->readJsonBody();
            if (!$data || !isset($data['titulo'])) {
                $this->jsonResponse(["error" => "Falta el titulo"], 400);
            }
            if ($this->modeloArticulo->create($data)) {
                $this->jsonResponse(["message" => "Artículo creado"], 201);
            }
            $this->jsonResponse(["error" => "Error al crear"], 500);
        }
    }

    // Detalle, borrado y actualización de un artículo
    public function articulo($id): void
    {
        $this->requireBasicAuth();
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method === 'GET') {
            $art = $this->modeloArticulo->getById($id);
            $art ? $this->jsonResponse($art, 200) : $this->jsonResponse(["error" => "No existe"], 404);
        }

        if ($method === 'DELETE') {
            if ($this->modeloArticulo->delete($id)) {
                $this->jsonResponse(["message" => "Artículo borrado"], 200);
            }
            $this->jsonResponse(["error" => "Error al borrar"], 500);
        }

        if ($method === 'PUT') {
            $data = $this->readJsonBody();
            if ($data && $this->modeloArticulo->update($id, $data)) {
                $this->jsonResponse(["message" => "Actualizado correctamente"], 200);
            } else {
                $this->jsonResponse(["error" => "Error al actualizar"], 500);
            }
        }
    }
}