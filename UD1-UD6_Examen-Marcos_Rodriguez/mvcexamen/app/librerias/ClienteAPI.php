<?php

namespace Mrs\Webcliente;

require_once __DIR__.'/../config/config.php';

class ClienteAPI
{
    private $apiUrl;
    private $basicUser;
    private $basicPass;

    public function __construct()
    {
        $this->apiUrl = API_URL;
        $this->basicUser = 'dwes';
        $this->basicPass = 'dwes';
    }

    private function request($metodo, $endpoint, $datos = null)
    {
        $url = $this->apiUrl.'/'.$endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

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
            'Authorization: Basic '.base64_encode($this->basicUser.':'.$this->basicPass),
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return [
                'success' => false,
                'error' => 'Error: '.$error,
                'status' => 0,
            ];
        }

        $data = json_decode($response, true);

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
