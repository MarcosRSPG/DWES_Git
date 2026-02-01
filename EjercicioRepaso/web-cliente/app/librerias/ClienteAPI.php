<?php

namespace Mrs\WebCliente;

require_once __DIR__.'/../config/config.php';

class ClienteAPI
{
    private $apiUrl;
    private $basicAuth;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->apiUrl = API_URL;
        $this->basicAuth = API_BASIC_USER.':'.API_BASIC_PASS;
    }

    private function getCookiesHeader()
    {
        if (empty($_SESSION['api_cookies']) || !is_array($_SESSION['api_cookies'])) {
            return null;
        }

        $pares = [];
        foreach ($_SESSION['api_cookies'] as $name => $value) {
            $pares[] = $name.'='.urlencode($value);
        }

        return 'Cookie: '.implode('; ', $pares);
    }

    private function saveCookiesFromHeaderText($headerText)
    {
        if (!isset($_SESSION['api_cookies'])) {
            $_SESSION['api_cookies'] = [];
        }

        $lineas = explode("\r\n", $headerText);
        foreach ($lineas as $linea) {
            if (stripos($linea, 'Set-Cookie:') === 0) {
                $cookie = trim(substr($linea, strlen('Set-Cookie:')));
                $parts = explode(';', $cookie, 2);
                $par = trim($parts[0]);

                if (strpos($par, '=') !== false) {
                    list($name, $value) = explode('=', $par, 2);
                    $_SESSION['api_cookies'][trim($name)] = trim($value);
                }
            }
        }
    }

    private function request($metodo, $endpoint, $datos = null)
    {
        $url = $this->apiUrl.'/'.$endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, true);

        switch (strtoupper($metodo)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if ($datos) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
                }
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                if ($datos) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));
                }
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic '.base64_encode($this->basicAuth),
        ];

        $cookieHeader = $this->getCookiesHeader();
        if ($cookieHeader) {
            $headers[] = $cookieHeader;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $fullResponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return [
                'success' => false,
                'error' => 'Error: '.$error,
                'status' => 0,
            ];
        }

        $headersText = substr($fullResponse, 0, $headerSize);
        $bodyText = substr($fullResponse, $headerSize);

        $this->saveCookiesFromHeaderText($headersText);

        $data = json_decode($bodyText, true);

        return [
            'success' => ($httpCode >= 200 && $httpCode < 300),
            'data' => $data,
            'status' => $httpCode,
        ];
    }

    public function get($endpoint)
    {
        return $this->request('GET', $endpoint);
    }

    public function post($endpoint, $datos)
    {
        return $this->request('POST', $endpoint, $datos);
    }

    public function put($endpoint, $datos)
    {
        return $this->request('PUT', $endpoint, $datos);
    }

    public function delete($endpoint)
    {
        return $this->request('DELETE', $endpoint);
    }
}
