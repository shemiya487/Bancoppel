<?php
// sendStatus.php
header("Content-Type: application/json");

function limpiar_txid($txid) {
    return preg_replace('/[^a-zA-Z0-9_]/', '', $txid);
}

$estado_dir = __DIR__ . "/estado";

if (!file_exists($estado_dir)) {
    mkdir($estado_dir, 0755, true);
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["txid"])) {
    $txid = limpiar_txid($_GET["txid"]);
    $file = "$estado_dir/estado_botones_$txid.json";

    if (file_exists($file)) {
        echo file_get_contents($file);
    } else {
        echo json_encode(["status" => "esperando"]);
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    $status = $input["status"] ?? "sin_status";
    $txid = limpiar_txid($_GET["txid"] ?? uniqid("manual_"));
    $file = "$estado_dir/estado_botones_$txid.json";

    file_put_contents($file, json_encode(["status" => $status]));
    echo json_encode(["ok" => true]);
    exit;
}

echo json_encode(["error" => "Método no permitido"]);
