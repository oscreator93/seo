<?php
include("./ApiClient.php");

header('Content-Type: application/json');

$client = new ApiClient();

$mode = $_GET["mode"] ?? "";

if ($mode == "login") {
    $client->login();
} else if ($mode == "create_metadata") {
    $client->createMetadata();
} else if ($mode == "update_metadata") {
    $id = $_GET['id'] ?? "";
    $client->updateMetadata($id);
} else if ($mode == "get_seo_list") {
    $client->getSeoList();
} else if ($mode == "change_password") {
    $client->changePassword();
} else if ($mode == "delete_metadata") {
    $id = $_GET['id'] ?? "";
    $client->deleteMetadata($id);
} else {
    http_response_code(400);
    echo json_encode(["error" => "Invalid mode"]);
}