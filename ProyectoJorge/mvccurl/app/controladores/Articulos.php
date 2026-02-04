<?php

namespace Cls\Mvc2app;

class Articulos extends Controlador
{
    private string $apiBase;

    public function __construct()
    {
        $this->apiBase = rtrim(API_BASE_URL, '/');
    }

    private function apiUrl(string $path): string
    {
        return $this->apiBase.'/'.ltrim($path, '/');
    }

    private function apiGet(string $path): array
    {
        if (!function_exists('curl_init')) {
            return [
                'ok' => false,
                'code' => 0,
                'data' => ['error' => 'Extensión cURL de PHP no habilitada (curl_init no existe).'],
                'raw' => '',
            ];
        }

        $ch = curl_init($this->apiUrl($path));

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
            ],

            // Basic Auth
            CURLOPT_USERPWD => API_BASIC_USER.':'.API_BASIC_PASS,

            // timeouts
            CURLOPT_CONNECTTIMEOUT => API_CONNECT_TIMEOUT,
            CURLOPT_TIMEOUT => API_TIMEOUT,
        ]);

        $raw = curl_exec($ch);
        $err = curl_error($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($raw === false) {
            return [
                'ok' => false,
                'code' => 0,
                'data' => ['error' => 'cURL error', 'detail' => $err],
                'raw' => '',
            ];
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            $decoded = ['raw' => $raw];
        }

        return [
            'ok' => ($code >= 200 && $code < 300),
            'code' => $code,
            'data' => $decoded,
            'raw' => $raw,
        ];
    }

    // GET http://mywww/DWES/ud6/mvccurl/articulos/index
    public function index(): void
    {
        $resp = $this->apiGet(API_ARTICULOS_LIST);

        $datos = [
            'titulo' => 'mvccurl: listado de articulos (consumiendo mvcapi con Basic Auth)',
            'http' => $resp['code'],
            'articulos' => $resp['ok'] ? $resp['data'] : [],
            'error' => $resp['ok'] ? null : $resp['data'],
        ];

        $this->vista('articulos/index', $datos);
    }

    // GET http://mywww/DWES/ud6/mvccurl/articulos/show/3
    public function show(int $id): void
    {
        $path = sprintf(API_ARTICULOS_ITEM, $id);
        $resp = $this->apiGet($path);

        $datos = [
            'titulo' => "mvccurl: ficha articulo #$id (consumiendo mvcapi con Basic Auth)",
            'http' => $resp['code'],
            'articulo' => $resp['ok'] ? $resp['data'] : null,
            'error' => $resp['ok'] ? null : $resp['data'],
        ];

        $this->vista('articulos/show', $datos);
    }

    // PUT http://mywww/DWES/ud6/mvccurl/articulos/show/3
    public function update(int $id): void
    {
        $path = sprintf(API_ARTICULOS_ITEM, $id);
        $resp = $this->apiGet($path);

        $datos = [
            'titulo' => "mvccurl: ficha articulo #$id (consumiendo mvcapi con Basic Auth)",
            'http' => $resp['code'],
            'articulo' => $resp['ok'] ? $resp['data'] : null,
            'error' => $resp['ok'] ? null : $resp['data'],
        ];

        $this->vista('articulos/update', $datos);
    }
}
