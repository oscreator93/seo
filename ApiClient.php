<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'loadenv.php';

class ApiClient
{
    private $serverUrl;
    private $session_id;
    private $apiKey;

    public function __construct()
    {
        $this->serverUrl = $_ENV['SERVER'] ?? "http://localhost:3000";
        $this->session_id = $_SESSION["session_id"] ?? "";
        $this->apiKey = $_ENV["NODE_API_KEY"] ?? "";
    }
    public function getVisitorIP()
    {
        $realIp = $_SERVER['REMOTE_ADDR'] ?? '';

        if (filter_var($realIp, FILTER_VALIDATE_IP) === false) {
            http_response_code(400);
            exit('Invalid IP address');
        }
        return $realIp;

    }
    public function getHost()
    {
        return $this->serverUrl;
    }
    public function request($endpoint, $data, $method)
    {
        $headers = [
            'x-Real-Ip: ' . $this->getVisitorIP(),
            'x-api-key: ' . $this->apiKey,
        ];

        if (is_array($data)) {
            // Form data
            $postFields = $data;
        } else {
            // JSON data
            $headers[] = 'Content-Type: application/json';
            $postFields = $data;
        }

        if (!empty($this->session_id)) {
            $headers[] = 'x-session-id: ' . $this->session_id;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => $headers,
        ));
        
        $response = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        http_response_code($statusCode);

        return [$response, $statusCode];
    }

    public function login()
    {
        $input = file_get_contents("php://input");
        [$response, $statusCode] = $this->request($this->serverUrl . '/api/seo/login', $input, "POST");

        if ($statusCode == 200) {
            $result = json_decode($response, true);
            $_SESSION['session_id'] = $result['session_id'];
        }

        echo $response;
    }


    public function is_logged_in()
    {
        [$response, $status_code] = $this->request($this->serverUrl . '/api/seo/logged_in', [], 'GET');

        if ($status_code != 200) {
            header("Location: index.php");
            exit;
        }

        return $response;
    }

    // Send Reference
    public function createMetadata()
    {
        $input = file_get_contents("php://input");

        [$response] = $this->request($this->serverUrl . '/api/meta', $input, "POST");

        echo $response;
    }

    public function getSeoList()
    {
        [$response] = $this->request($this->serverUrl . '/api/meta', [], "GET");

        echo $response;
    }

    public function changePassword()
    {
        $input = file_get_contents("php://input");

        [$response] = $this->request($this->serverUrl . '/api/change_password', $input, "POST");

        echo $response;
    }
    public function logout()
    {
        [$response, $statusCode] = $this->request($this->serverUrl . '/api/logout', [], "POST");

        $result = json_decode($response, true);

        if ($statusCode == 200) {
            header("Location: index.php?message=" . urlencode($result['message']));
            exit();
        }
    }
    public function getPages()
    {
        [$response] = $this->request($this->serverUrl . '/api/pages', [], "GET");

        return $response;
    }
    public function getMetadata($id)
    {
        [$response] = $this->request($this->serverUrl . '/api/meta/' . $id, [], "GET");

        return $response;
    }
    public function updateMetadata($id)
    {
        $input = file_get_contents("php://input");

        [$response] = $this->request($this->serverUrl . '/api/meta/' . $id, $input, "PUT");

        echo $response;
    }
    public function deleteMetadata($id)
    {
        $input = file_get_contents("php://input");

        [$response] = $this->request($this->serverUrl . '/api/meta/' . $id, $input, "DELETE");

        echo $response;
    }
}